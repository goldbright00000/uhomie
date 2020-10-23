<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Membership,Role,User};
use App\DbViews\RoleMembershipUser;
class MembershipController extends Controller
{
    //
    public function getMemberships( Request $request ){
      $records = Membership::where([ "enabled" => true ])->get();
      return response([ "records" => $records ]);
  	}
    public function getMembershipsForRole( Request $request ){
      $db = RoleMembershipUser::query();
      $db_roles_memberships = $db->where("enabled",true)->get();
      $records = [];
      foreach (Role::all() as $r) {
        $role = (object)[
          'id' => $r->id,
          "role_name" => $r->name,
          "role_hidden" => $r->hidden,
          'memberships' => []
        ];
        foreach ($db_roles_memberships as $rm) {
          if ($rm->role_id == $r->id) {
            $role->memberships[] = $rm;
          }
        }
        $records[] = $role;
      }
      return response([
        "records" => $records
      ]);
  	}
    public function getUserHasMembership( Request $request ){
      $user = User::find($request->userId);
      $user_has_membership = null;
      if ( !is_null($user) ) {
        if ( $user->memberships->contains($request->membershipId) && $user->memberships()->where('memberships.id', $request->membershipId) ) {
          $user_has_membership = [
            "id" => $user->id,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
            "email" => $user->email,
            "membership" => $user->memberships()->where('memberships.id', $request->membershipId)->withPivot(['expires_at', 'purchased_at'])->first()->load("role")
          ];
        }
      }
      return response([ "user_has_membership" => $user_has_membership ]);
  	}
    public function updateUserHasMembership( Request $request ){
      $query = User::find( $request->userId )->memberships()->updateExistingPivot($request->membershipId, [ "membership_id" => $request->membershipIdSelected, "purchased_at" => \Carbon\Carbon::parse($request->purchased_at)->format('Y-m-d') ,"expires_at" => \Carbon\Carbon::parse($request->expires_at)->format('Y-m-d') ]);
      return response([ 'operation' => true ]);
  	}
    public function detachUser( Request $request ){
      $user = User::find( $request->userId );
      $user->memberships()->detach($request->membershipId);
      $user->setDefaultMembership($request->roleId);
      return response([ 'operation' => true ]);
  	}
    public function update( Request $request ){
  		$membership = Membership::find($request->membershipId);
      $membership->features = json_encode($request->features);
      $membership->save();
      return response([ "membership_data" => $membership ]);
  	}
    public function getMembership( Request $request ){
      // $membership = Membership::find($request->membershipId)->first();
      // return response([
      //   "id" => $membership->id,
      //   "name" => $membership->name,
      //   "enabled" => $membership->enabled,
      //   "features" => json_decode($membership->features,true),
      //   "role_id" => $membership->role_id
      // ]);
      $membership = Membership::find($request->membershipId);
      return response([
          "membership" => [
            "id" => $membership->id,
            "name" => $membership->name,
            "role" => $membership->role->name,
            "features" => json_decode($membership->features)
          ]
        ]);
    }

    public function getUsersHasMembership( Request $request ){
      $membership = Membership::find($request->membershipId)->load('role');
      return response([
        "membership" => $membership,
        "users" => $membership->users
      ]);
    }
    public function getUsersMultiRole( Request $request ){
      $users = [];
      foreach (User::all() as $u) {
        if ($u->memberships()->count() > 1) {
          $user = (object)[
            "id" => $u->id,
            "firstname" => $u->firstname,
            "lastname" => $u->lastname,
            "email" => $u->email,
            "memberships" => $u->memberships->load("role"),
            "memberships_count" => $u->memberships()->count()
          ];
          $users[] = $user;
        }
      }
      return response([
        "users" => $users,
        "users_count" => count($users)
      ]);
    }
    public function getUsersDefault( Request $request ){
      $db = RoleMembershipUser::query();
      $roles_memberships = $db->where("enabled",false)->get();
      return response([ "roles_memberships" => $roles_memberships ]);
    }
}
