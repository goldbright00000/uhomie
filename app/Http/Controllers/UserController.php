<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\{User, File, Role,Membership, Photo, Configuration};
use Authy\AuthyApi;
use App\Notifications\{MailVerification, PhoneVerification, TwilioPush};
use App\DbViews\{Property as vProperty, PropertyWU as vPropertyWu};
use App\DbViews\Project as vProject;
use App\DbViews\Agent as vAgent;
use \Exception;
use App\Http\Controllers\Controller;
use App\Library\Services\OneTouch;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TerceraClave;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Carbon;
use Image;

class UserController extends Controller

{
    /**
     * Check email
     * @param Request $request
     * @return type
     */
    public function checkMail(Request $request){
  		return is_null(User::where('email',$request->email)->first()) ? json_encode(true) : json_encode(false);
  	}

    /**
     * Check collateral email
     * @param Request $request
     * @return type
     */
    public function checkCollateralMail(Request $request){
        $user = User::where('email',$request->email)->first();
        if (is_null($user)) {
            return json_encode(['exist' => false, 'data' => null]);
        }elseif (\Auth::user()->id == $user->id) {
            return json_encode(['exist' => true, 'data' => null]);
        }else{
            return json_encode(['exist' => true, 'data' => $user]);
        }
    }
    public function getRolesUser(Request $request){
        $user = \Auth::user();
        if($user != null){
            $roles = [];
            foreach ($user->memberships as $key => $m) {
            $roles[] = $m->role;
            }
            return response( $roles );
        } else {
            return redirect()->route('login');
        }
        
    }
    /**
     * Check document number
     * @param Request $request
     * @return type
     */
    public function checkDocumentNumber(Request $request){
        $user = User::where('document_number',$request->document_number)->first();
        return is_null($user) || $user->id == \Auth::user()->id  ? json_encode(['valid' => true]) : json_encode(['valid' => false]);
    }
    /**
     * Check phone number
     * @param Request $request
     * @return type
     */
    public function checkPhoneNumber(Request $request){
        $user = User::where('phone',$request->phone)->first();
        return is_null($user) || $user->id == \Auth::user()->id  ? json_encode(['valid' => true]) : json_encode(['valid' => false]);
    }
    /**
     * Get Data Auth
     * @param Request $request
     * @return type
     */
    public function getDataAuth(){
        return is_null(\Auth::user()) ? json_encode(['valid' => false,'data'=>null]) : json_encode(['valid' => true,'data'=>\Auth::user()]);
    }


    /**
     * Save user
     * @param Request $request
     * @return redirect
     */
    public function register(Request $request) {
        $this->validate($request, [
            'g-recaptcha-response'=>'required|recaptcha',
            'email' => 'required|string|email|max:191|unique:users,email',
            'provider' => 'nullable|string|exists:providers,name',
            'provider_id' => 'nullable|string', //|unique:users,provider_id' // TO-DO: crear validador de provider+provider_id sea Unique, lo dejaremos asi por mientras por la baja probabilidad de coincidencia entre id de distintos providers
            'referido' => 'nullable|boolean'
            ]);
        $user = new User();
        
        $user->firstname = $request->first_name;
        $user->lastname  = $request->last_name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->activation_token = User::generateToken();
        $user->save();
        
        if( isset($request->provider) && isset($request->provider_id) ){
            $provider = \App\Provider::where('name', $request->provider)->first();
            if($provider){
                if( 0 == DB::table('user_provider')->where('provider_id', $provider->id)->where('user_provider_id', $request->provider_id)->count() ){
                    $user->providers()->attach($provider->id, ['user_provider_id' => $request->provider_id]);
                    $vinculacion_exito = true;
                } else {
                    $vinculacion_exito = false;
                }
            }
            
        }
        File::generateIdFiles($user);

        $user->notify(new MailVerification($user->activation_token));

        $user->setDefaultMembership($request->role);
        $user->setDefaultUriRegistrationRole();

        if (\Auth::attempt(['email' => $user->email, 'password' => $request->password])) {
            if( isset($vinculacion_exito) ){
                if( $vinculacion_exito ){
                    return redirect('/');
                } else {
                    return redirect('/')->with(['error_bag' => ['message' => "Tu cuenta en ".$provider->name." ya se encuentra vinculada a una cuenta en uHomie. De todas formas puedes continuar el proceso de registro con los datos ingresados." ]]);
                }
                
            } else {
                if( $request->referido == 1 ){
                    return redirect('/')->with('referido_flag', 1);
                }else{
                    return redirect('/');
                }
                
            }
            
        }else {
            return redirect('/')->with(['error_bag' => ['message' => "Ha ocurrido un error iniciando sesion"]]);
        }
    }
    /**
     * Email Verificacion
     * @param Request $request
     * @return redirect
     */
    public function mailVerification($activation_token) {
        $user = User::where('activation_token',$activation_token)->first();
        if (is_null($user) || $user->mail_verified) {
            return redirect('/')->with(['error_bag' => ['message' => "La página solicitada no existe"]]);
        }
        $user->mail_verified = true;
        $user->save();

        if (!\Auth::user()) {
            \Auth::login($user);
        }
        $user = User::find(\Auth::user()->id);
        $configuration = Configuration::where('name', 'phone-verify')->first();
        $page = $user->setDefaultUriRegistrationRole();
        if($configuration->enabled == 1){
            return redirect('users/phone-verify')->with('user', \Auth::user());
        } else {
            $user->phone_verified = true;
            $user->save();
            $user->updateProfileRedirect($page,$user->memberships()->first()->role->name);
            return redirect()->route($page);
        }
    }
    /**
     * Phone Verify
     * @param Request $request
     * @return redirect
     */
    public function phoneVerify() {
        if (!\Auth::user() || \Auth::user()->phone_verified) {
            return redirect('/')->with(['error_bag' => ['message' => "La página solicitada no existe"]]);
        }
        return view('users.phone-verify')->with('user', \Auth::user());
    }

    /**
     * Phone Verification
     * @param Request $request
     * @return redirect
     */
    public function phoneVerification(Request $request) {
        $code  = $request->code;
        $phone = str_replace(' ', '', $request->phone);
        $authyApi = new AuthyApi(env('API_KEY'));
        $response = $authyApi->phoneVerificationStart($phone, $code, 'sms');
        if ($request->ajax()) {
            return response(['respuesta' => ['phone' => $phone]]);
        }
        return redirect('users/check-phone-verification/'.$code.'/'.$phone);
    }
    /**
     * Show Phone Verification form
     * @param Request $request
     * @return json
     */
    public function checkVerificationForm($code, $phone){
        return view('users.phone-verification')->with(['user' => \Auth::user(),
                                                        'code' => $code,
                                                        'phone' => $phone]);
    }

    /**
     * Check Phone Verification
     * @param Request $request
     * @return json
     */
    public function checkVerificationToken(Request $request){
        $authyApi = new AuthyApi(env('API_KEY'));
        $result = $authyApi->phoneVerificationCheck($request->phone, $request->code, $request->token);
        $user = \Auth::user();
        $page = $user->setDefaultUriRegistrationRole();

        $data  = array('page' => route($page), 'result' => $result, 'status_code' => $result->ok() ? 200 : 202);
        if ($result->ok()) {
            $user->phone_verified = true;
            $user->phone = $request->phone;
            $user->phone_code = $request->code;
            $authy_user = $authyApi->registerUser($user->email, $user->phone, $user->phone_code);
            if($authy_user->ok()){
                $user->authy_id = $authy_user->id();
            }else {
                # SOMETHING IS WRONG!
            }
            $user->save();
            $user->updateProfileRedirect($page,$user->memberships()->first()->role->name);
            $user->notify(new PhoneVerification());
            $user->notify(new TwilioPush("Este numero de telefono ha sido verificado con exito. Saludos, uHomie."));
        }else{
            /**
                TO DO
            */
            /**
             Response of negative Code
            */
        }

        return response()->json($data);
    }

    /**
     * send verification code via SMS.
     *
     * @param  Illuminate\Support\Facades\Request  $request;
     * @return Illuminate\Support\Facades\Response;
     */
    protected function sendVerificationCodeSMS(Request $request) {
        $authyApi = new AuthyApi(env('API_KEY'));
        $user = \Auth::user();
        $authyApi->requestSms($user->authy_id);

        return response()->json(['success' => 'Verification Code sent via SMS succesfully.']);
    }

   /**
    * send verification code via Voice Call.
    *
    * @param  Illuminate\Support\Facades\Request  $request;
    * @return Illuminate\Support\Facades\Response;
    */
    protected function sendVerificationCodeVoice(Request $request) {
        $authyApi = new AuthyApi(env('API_KEY'));
        $user = \Auth::user();
        $authyApi->phoneCall($user->authy_id);

        return response()->json(['success' => 'Verification Code sent via Voice Call succesfully.']);
    }

   /**
    * verify token.
    *
    * @param  Illuminate\Support\Facades\Request  $request;
    * @return Illuminate\Support\Facades\Response;
    */
    protected function verifyToken(Request $request) {
        $authyApi = new AuthyApi(env('API_KEY'));
        $user = \Auth::user();
        $verification = $authyApi->verifyToken($user->authy_id, $request->token);
        if($verification->ok()){
            return response()->json(['success' => 'Token verified successfully.']);
            if($request->verify == 'phone'){
                $user->phone_verified = 1;
                $user->save();
            }
        }else {
            return response()->json(['success' => 'Token verification failed.']);
        }
    }

    public function newRoleRegistration(Request $request){
        $user = \Auth::user();
        $role = Role::find($request->role_id);
        // verifica
        if ( is_null($role) ) {
            return abort(404);
        }
        $roles = false;
      
        /*foreach ($user->memberships as $m) {
            if ( $m->role_id == $request->role_id ) {
                $roles = true;
                break;
            }
        }*/

        if($user != null){
            foreach ($user->memberships as $m) {
                if ( $m->role_id == $request->role_id ) {
                    $roles = true;
                    break;
                }
            }
        } else {
            return redirect()->route('login');
        }

      if ( $roles || $role->hidden ) { 
            switch ($role->slug){
                case 'tenants':
                    return redirect("/users/profile/tenant#"); 
                    break;
                case 'owners':
                    return redirect("/users/profile/owner#"); 
                    break;
                case 'agents':
                    return redirect("/users/profile/agent#"); 
                    break;
                case 'services':
                    return redirect("/users/profile/service#"); 
                    break;
                case 'collaterals':
                    return redirect("/users/profile/collateral#"); 
                    break;
                default:
                    
                    break;
            }
            
            return redirect("/"); 
        }
      $user->setDefaultMembership($request->role_id);
      $page = "users.".$role->slug.".first-step";
      $user->updateProfileRedirect($page,$role->name);
      return redirect( )->route($page);
    }

    public function update(Request $request) {
        
    }

    
    function updatePhoto(Request $request) {
        if ($request->hasFile('avatar')) {
            $user = Auth::user();
            if (Storage::exists(str_replace('storage', 'public',$user->photo))){
                Storage::delete(str_replace('storage', 'public',$user->photo));
            }
            $originalImage= $request->file('avatar');
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path().'/storage/avatars/';
            $width = $thumbnailImage->width();
            $height = $thumbnailImage->height();
            if($width > $height){
                $thumbnailImage->resize(null,500, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $thumbnailImage->resize(500,null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $thumbnailImage->crop(500,500);
            $thumbnailImage->save($originalPath.time().$thumbnailImage->filename.'.jpg');
            $photo_dir = '/storage/avatars/'.$thumbnailImage->basename;
            $user->photo = $photo_dir;
            $user->save();
            return response()->json(['code' => 200, 'url' => $user->photo, 'description' => 'Image Updated']);
        } else {
            return response()->json(['code'=>400, 'description' => 'File Not Found']);
        }
    }
    // Editado por AA
    public function sendMailCode(Request $request) {
        $user = \Auth::user();
        if ($user){
            $numero = rand(0,9999999);
            $clave = str_pad($numero,7, '0', STR_PAD_LEFT);
            Mail::to($user->email)->send( new TerceraClave('Su clave es: '.$clave));
            $user->tercera_clave = \Hash::make($clave);
            $user->save();
            $v['success'] = 1;
            return response($v, 200);
        } else {
            return response(401);
        }
    }
    // Editado por AA
    public function verifyMailCode(Request $request) {
        $user = \Auth::user();
        if ($user){
            if( \Hash::check($request->token, $user->tercera_clave) ){ 
                $v['success'] = 'Token verified successfully.';
                $user->tercera_clave = null;
                if($request->verify == 'email'){
                    $user->mail_verified = 1;
                }
                $user->save();
                return response($v, 200);
            } else {
                $v['success'] = 'Token verification failed.';
                return response($v, 200);
            }
        } else {
            $v['success'] = 'Token verification failed.';
            return response($v, 200);
        }
    }
    public function vincularCuenta(Request $request, $provider)
    {
        if( $url = $request->query('return')){
            session(['returnUrl' => $url]);
            return Socialite::driver($provider)->redirect();
        }
        
        
    }

    public static function obtenerInfoDesarrollador($usuario)
    {
        $user = User::findOrFail($usuario);
        $contrato = $user->contracts()->get();
        $postulaciones = $user->applications()->get();
        dd(['usuario' => $user, 'contratos' => $contrato, 'postulaciones' => $postulaciones]);
    }

    public function getProfile(Request $request){
        
        return view('pages.user-details');
    }

    public function getProfileAgent(Request $request){
        return view('pages.agent-details');
    }

    public function getInfoProfile(Request $request){
        $info = [];
        $user = User::find($request->id);
        $membership = $user->getOwnerMerbershipOnce();
        $address = explode(",", $user->address);
        $address_short = '';
        foreach ($address as $key => $value) {
            if($key == 1){
                $address_short = $address_short . str_replace(" ", "", $value);
            }
            if($key == 2){
                $address_short = $address_short . $value . ',' ;
            }
            if($key > 2){
                if($key == count($address) - 1){
                    $address_short = $address_short . $value;
                } else {
                    $address_short = $address_short . $value . ',' ;
                }
            }

        }
        $id_front = File::where('user_id', $user->id)->where('name', 'id_front')->first();

        if($id_front->verified == 1){
            $verified = true;
        } else {
            $verified = false;
        }

        $properties = $this->getProperties($request->id);

        $info['data'] = (object)[
            'name' => $user->firstname . ' ' . $user->lastname,
            'address' => $address_short,
            'imagen' => $user->photo,
            'verified' => $verified,
            'email' => (boolean)$user->mail_verified,
            'phone' => (boolean)$user->phone_verified,
            'membership' => strtolower($membership->name),
            'created_at' => Carbon::parse($user->created_at)->format('Y-m-d'),
            'properties' => $properties->original
        ];

        return $info;
    }

    public function getInfoProfileAgent(Request $request){
        $info = [];
        $user = User::find($request->id);
        $company = $user->getAgentCompany();
        $membership = $user->getAgentMerbershipOnce();
        $address = explode(",", $user->address);
        $address_short = '';
        foreach ($address as $key => $value) {
            if($key == 1){
                $address_short = $address_short . str_replace(" ", "", $value);
            }
            if($key == 2){
                $address_short = $address_short . $value . ',' ;
            }
            if($key > 2){
                if($key == count($address) - 1){
                    $address_short = $address_short . $value;
                } else {
                    $address_short = $address_short . $value . ',' ;
                }
            }

        }
        $id_front = File::where('user_id', $user->id)->where('name', 'id_front')->first();

        if($id_front->verified == 1){
            $verified = true;
        } else {
            $verified = false;
        }

        $properties = $this->getProperties($request->id);
        $projects = $this->getProjects($company->id);

        $logo = Photo::where('company_id', $company->id)->where('logo', 1)->first();

        $company->logo = $logo ? $logo->path : null;

        $info['data'] = (object)[
            'name' => $user->firstname . ' ' . $user->lastname,
            'address' => $address_short,
            'imagen' => $user->photo,
            'verified' => $verified,
            'email' => (boolean)$user->mail_verified,
            'phone' => (boolean)$user->phone_verified,
            'membership' => strtolower($membership->name),
            'created_at' => Carbon::parse($user->created_at)->format('Y-m-d'),
            'properties' => $properties->original,
            'company' => $company,
            'projects' => $projects->original
        ];
        return $info;
    }

    private function getProperties($id) {
		$select_fields = [
			'property_id',
			'path',
			'name',
			'description',
			'rent',
			'membership_name',
			'verified',
			'demand',
			'bathrooms',
			'bedrooms',
			'private_parking',
			'available',
			'type_stay'
		];

		$wu = false;
		//vPropertyWu falla :: reporte Lunes - 15 julio
		if (Membership::checkTenantMemberships()) {
			$wu = true;
			$qb = vPropertyWu::query();
			//dd($qb->select()->distinct()->limit($limit)->offset($offset)->get());
			$select_fields = array_merge($select_fields, ['score','favorite', 'applied']);
			
			$qb->where('user_id', \Auth::user()->id);
			
		}else {
			$qb = vProperty::query();
        }
		
        $qb->where('owner_id', $id);

        $qb->where('is_project', 0);
        
        $qb->where('type_user', 5);
		
		$db_properties = $qb->select($select_fields)->distinct()->get();
		
		$properties = [];
		foreach ($db_properties as $dbp) {
                
            $property = (object)[
                'id' => (int)$dbp->property_id,
                'imagePath' => $dbp->path,
                'name' => $dbp->name,
                'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $dbp->name))),
                'description' => $dbp->description,
                'price' => (float)$dbp->rent,
                'scoring' => (int)$dbp->score,
                'membership' => strtolower($dbp->membership_name),
                'verified' => (bool)$dbp->verified,
                'demand' => (int)DB::select('select sf_demand_property(?) as demand', [$dbp->property_id])[0]->demand,
                'favorite' => (bool) $dbp->favorite,
                'applied' => (bool) @$dbp->applied,
                'bathNumber' => (int)$dbp->bathrooms,
                'roomNumber' => (int)$dbp->bedrooms,
                'parkingNumber' => (int)$dbp->private_parking,
                'imagesDir' => asset('images'),
                //lat & lng
                'latitude' => isset($p->latitude) ? $p->latitude : '',
                'longitude' => isset($p->longitude) ? $p->longitude : '',
                'available' => (boolean)$dbp->available,
                'type_stay' => $dbp->type_stay
            ];
            $properties[] = $property;
		}

		return response($properties);
    }
    private function getProjects($id){

        $select_fields = [
			'property_id',
			'path',
			'name',
			'description',
			'rent',
			'membership_name',
			'verified',
			'demand',
			'bathrooms',
			'bedrooms',
			'private_parking',
			'available',
			'type_stay',
			'latitude',
			'longitude',
			'type_user',
		];

			/*$project_id = Project::find($request->company_id);
			$qb = vProject::query()->join('properties', 'v_agent.project_id', '=', 'properties.id');
			$qb->where('v_agent.company_id', $project_id->company_id);*/
			$qb = vProperty::query();
			$qb->where('is_project',1);
			$qb->where('company_id', $id);

		$db_projects = $qb->select($select_fields)->distinct()->orderBy('property_id', 'DESC')->get();

		

		$projects = [];
		foreach ($db_projects as $dbp) {
            $project = (object)[
                'id' => (int)$dbp->project_id,
                'imagePath' => $dbp->path,
                'name' => $dbp->name,
                'slug' => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $dbp->name))),
                'description' => $dbp->description,
                'price' => (float)$dbp->rent,
                'membership' => strtolower($dbp->membership_name),
                'imagesDir' => asset('images'),
                'latitude' => isset($p->latitude) ? $p->latitude : '',
                'longitude' => isset($p->longitude) ? $p->longitude : ''
            ];
            $projects[] = $project;
		}
		return response($projects);
    }
}
