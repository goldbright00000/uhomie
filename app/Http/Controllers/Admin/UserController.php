<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{User, File, Role, Membership, Country, CivilStatus, City, PropertyType, PropertyFor };
use App\DbViews\UserCountRole;
use App\Notifications\{MailVerification,UserPasswordGenerated};
class UserController extends Controller
{
    public function getUsers( Request $request ){
      $db_users = User::select('id','firstname', 'lastname','email', 'photo','phone_code','phone', 'document_type', 'document_number', 'address')
                        ->with('memberships','memberships.role')
                        ->with('payments')
                        ->with('files')
                        ->get();
                
      return response([ 'records' => $db_users ]);
  	}

    public function getOwners( Request $request ){
      $owners = User::where([ "memberships.role_id" => Membership::TYPE_OWNER, "memberships.enabled" => 1 ])
                        ->join('users_has_memberships', 'users_has_memberships.user_id', '=', 'users.id')
                        ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
                        ->join('roles', 'memberships.role_id', '=', 'roles.id')
                        ->select(['users.id','users.firstname','users.lastname','users.email'])
                        ->get();
      return response([ 'records' => $owners ]);
  	}

    // Usuarios por role 
    public function getUsersByRole( Request $request ){

      $roleId = $request->roleId;

      $db_users = User::select('firstname', 'lastname', 'users.id','email')
                        ->join('users_has_memberships', 'users_has_memberships.user_id', '=', 'users.id')
                        ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
                        ->with('memberships','memberships.role')
                        ->where(["role_id" => $roleId])
                        ->distinct()
                        ->get();
      return response([ 'records' => $db_users ]);
    }

    public function create( Request $request ){
      $user = new User();
      $user->firstname = $request->firstname;
      $user->lastname = $request->lastname;
      $user->email = $request->email;
      $password = $user->generateStrongPassword();
      $user->password = \Hash::make($password);
      $user->activation_token = User::generateToken();
      $user->save();
      File::generateIdFiles($user);
      $user->notify(new MailVerification($user->activation_token));
      $user->notify(new UserPasswordGenerated($password));
      $user->setDefaultMembership($request->role);
      $user->setDefaultUriRegistrationRole();
  		return response( [ 'operation' => true, 'user' => $user ]);
  	}
    public function update( Request $request ){
  		$user = User::find($request->userId);
      $user->firstname = $request->firstname;
      $user->lastname = $request->lastname;
      $user->email = $request->email;
      $user->phone_verified = $request->phone_verified;
      $user->active = is_null($request->active) ? 0 : $request->active;
      $user->save();
      return response([ "operation" => true ]);
  	}
    public function getUser( Request $request ){
  		$user = User::where([ "id" => $request->userId ])
                    ->with('memberships','memberships.role')
                    ->with('city')
                    ->with('files')
                    ->first();
      return response([ "user" => $user ]);
  	}
    public function generatePassword( Request $request ){
  		$user = User::find($request->userId);
      $password = $user->generateStrongPassword();
      $user->password = \Hash::make($password);
      $user->notify(new UserPasswordGenerated($password));
      $user->save();
      return response([ "operation" => true ]);
  	}
    public function delete( Request $request ){
  		$user = User::find($request->userId);
      $user->email = '_'+$user->email;
      $user->save();
      $user->delete();
      return response([ "operation" => true ]);
  	}
    public function getRoles(Request $request){
      $roles = User::where([ "users.id" => $request->userId ])
            ->join('users_has_memberships', 'users_has_memberships.user_id', '=', 'users.id')
            ->join('memberships', 'users_has_memberships.membership_id', '=', 'memberships.id')
            ->join('roles', 'memberships.role_id', '=', 'roles.id')
            ->select('roles.*')
            ->get();
      return response([ "records" => $roles ]);
  	}
    public function newRole(Request $request){
      $user = User::find($request->userId);
      $role = Role::find($request->roleId);
      // verifica
      if ( is_null($role) ) {
        return abort(404);
      }
      $roles = false;
      foreach ($user->memberships as $m) {
       if ( $m->role_id == $request->roleId ) {
          $roles = true;
          break;
       }
      }
      if ( $roles || $role->hidden ) { return response([ "operation" => false ]); }
      $user->setDefaultMembership($request->roleId);
      $page = "users.".$role->slug.".first-step";
      $user->updateProfileRedirect($page,$role->name);
      return response([ "operation" => true ]);
  	}
    public function getCollateral( Request $request ){
  		$user = User::find($request->userId);
      $collateral = $user->collateral;
      return response([ "collateral" => $collateral ]);
  	}
    public function getAgentCompany( Request $request ){
  		$user = User::find($request->userId);
      $agent_company = $user->getAgentCompany();
      return response([ "company" => $agent_company ]);
  	}
    public function getServiceCompany( Request $request ){
  		$user = User::find($request->userId);
      $service_company = $user->getServiceCompany();
      return response([ "company" => $service_company ]);
    }
    public function getCities(){
      $cities = City::all();
      return response(["cities" => $cities]);
    }
    public function getDescriptors(){
      $tenants = UserCountRole::where(["role_id" => Membership::TYPE_TENANT])->first()->u_count;
      $owners = UserCountRole::where(["role_id" => Membership::TYPE_OWNER])->first()->u_count;
      $agents = UserCountRole::where(["role_id" => Membership::TYPE_AGENT])->first()->u_count;
      $services = UserCountRole::where(["role_id" => Membership::TYPE_SERVICE])->first()->u_count;
      $collateral = UserCountRole::where(["role_id" => Membership::TYPE_COLLATERAL])->first()->u_count;
      return response([
        "tenants" => $tenants,
        "owners" => $owners,
        "agents" => $agents,
        "services" => $services,
        "collateral" => $collateral
      ]);
    }
    public function saveDataFile(Request $request){
      $data = $request->file;
      $file = File::find($data['id']);
      $file->verified = $data['verified'];
      $file->verified_ocr = $data['verified_ocr'];
      $file->val_date = $data['val_date'];
      $file->factor = $data['factor'];
      if($file->save()){
        return response(['mensaje' => 'La información del archivo ha sido cambiada'],200);
      } else {
        return response(500);
      }
    }
}
