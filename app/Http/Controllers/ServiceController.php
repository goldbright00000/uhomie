<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Auth;
use Carbon\Carbon;
use App\{Service,ServiceList,ServiceType,Country,Commune,CivilStatus,File,Company,Membership,Photo};
use App\Notifications\{ServiceProfile};
use Illuminate\Support\Facades\DB;
use App\Payment;

class ServiceController extends Controller
{
    public function __construct()
	  {
	      $this->middleware('service-memberships-check')->only([
					'registrationSecondStepThree',
          'registrationSecondStepFour'
				]);
	  }
    public function registrationFirstStep(){
		    return view('users.services.forms.first-step')->with(['user' => \Auth::user()]);
    }
    public function registrationFirstStepOne(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {

        return view('users.services.forms.first-step-one')->with(['user' => $user,
                                                                'countries' => Country::all(),
                                                                'civil_status' => CivilStatus::all()]);
        }elseif($request->isMethod('post')){

            $user->saveBasicData($request);
            $user->updateProfileRedirect('users.services.first-step.two','Servicio');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('users.services.first-step.two');
            }
        }

    }
    public function registrationFirstStepTwo(Request $request){

        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.services.forms.first-step-two')->with(['user' => \Auth::user(),'cities' => Country::find(39)->cities]);
        }elseif($request->isMethod('post')){
          $request->userId = $user->id;
          $request->invoice = 0;
			    $request->sii = 0;
          if (!is_null($user->getServiceCompany())) {
                $company = $user->getServiceCompany();
          }else{
            $company = new Company();
          }
          $company->saveBasicData($request);
            $user->saveLocationData($request);
            $user->updateProfileRedirect('users.services.second-step','Servicio');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('users.services.second-step');
            }
        }
    }
    public function registrationSecondStep(){
		    return view('users.services.forms.second-step')->with(['user' => \Auth::user()]);
    }
    public function registrationSecondStepOne(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {

            return view('users.services.forms.second-step-one')->with(['user' => \Auth::user()]);
        }elseif($request->isMethod('post')){

            if (!is_null($user->getServiceCompany())) {
              $company = $user->getServiceCompany();
            }else{
              $company = new Company();
            }
            $files = $request->files->all();

            foreach ($files as $fileName => $file) {
                try {
                    $db_file = $user->files()->where('name', $fileName)->first();
                    //dd('users/'.$user->id.'/files/'.$fileName);
                    if ( \Storage::disk('local')->exists( 'users/'.$user->id.'/files/'.$fileName) ) {
                        \Storage::disk('local')->delete( 'users/'.$user->id.'/files/'.$fileName) ;
                    }
                    \Storage::disk('local')->putFileAs('users/'.$user->id.'/files/',$file,$fileName);
                    // if (!$request->verified) { $db_file->verified = $request->verified; }
                    $db_file->original_name = $file->getClientOriginalName();
                    $db_file->path = 'users/'.$user->id.'/files/'.$fileName;
                    // Cuando se vayan agregando mas validadores, se tienen que agregar aqui.
                    //if( $fileName == 'id_front' || $fileName == 'id_back' ) $db_file->verified_ocr = 1;
                    
                    $db_file->save();
                } catch (Exception $e) {
                }
            }
            //$company->saveBasicData($request);
            $company->invoice = $request->invoice;
            $company->name = $request->name;
            $company->rut = $request->rut;
            $company->giro = $request->giro;
            $company->phone = $request->phone;
            $company->website = $request->website;
            $company->description = $request->description;

            $company->save();
            if (!$request->invoice) {
              $company->personal_publish = 1;
              $user->updateProfileRedirect('users.services.memberships','Servicio');
            }
            
            $user->updateProfileRedirect('users.services.second-step.two','Servicio');
            $company->save();
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                if ($request->invoice) {
                  return redirect()->route('users.services.second-step.two');
                }else{
                  return redirect()->route('users.services.memberships');
                }
            }
        }
    }

    public function registrationSecondStepTwo(Request $request){

        $user = \Auth::user();
        if ($request->isMethod('get')) {
            return view('users.services.forms.second-step-two')->with(['user' => \Auth::user()]);
        }elseif($request->isMethod('post')){
            $company = $user->getServiceCompany();
            $company->sii = $request->sii;
            $company->personal_publish = $request->personal_publish;
            $company->save();
            $user->updateProfileRedirect('users.services.memberships','Servicio');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('users.services.memberships');
            }
        }
    }
    public function registrationMembershipsForm(){

      $memberships = Membership::getServiceMemberships();
      return view('users.services.forms.memberships')
      ->with(['user' => \Auth::user(),
        'memberships' => $memberships,
        'route' => 'users.services.memberships-checkout',
        'close' => 'start'
      ]);
    }

    public function registrationMembershipsFormBack(Request $request){
      $user = \Auth::user();
      $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        switch($payment->status){
            case 0:
                $payment->statusname = 'Anulada';
            break;
            case 2:
                $payment->statusname = 'Cancelada';
            break;
            default: 
                $payment->statusname = 'Inconclusa';
            break;
        }
      $memberships = Membership::getServiceMemberships();
      return view('users.services.forms.memberships-back')->with(['user' => \Auth::user(),
      'memberships' => $memberships,
      'payment'=>$payment,
      'route'=>'users.services.memberships-checkout']);
    }

    public function registrationMembershipsFormUpdate(){

      $memberships = Membership::getServiceMemberships();
      return view('users.services.forms.memberships')
      ->with(['user' => \Auth::user(),
        'memberships' => $memberships,
        'close' => 'profile.service',
        'route' => 'users.services.memberships-checkout-update'
      ]);
    }


    public function registrationMembershipsFormBackUpdate(Request $request){
      $user = \Auth::user();
      $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        switch($payment->status){
            case 0:
                $payment->statusname = 'Anulada';
            break;
            case 2:
                $payment->statusname = 'Cancelada';
            break;
            default: 
                $payment->statusname = 'Inconclusa';
            break;
        }
      $memberships = Membership::getServiceMemberships();
      return view('users.services.forms.memberships-back')->with(['user' => \Auth::user(),
      'memberships' => $memberships,
      'payment'=>$payment,
      'route' =>'users.services.memberships-checkout-update']);
    }

    public function registrationMembershipCheckout(Request $request){
      $user = \Auth::user();
      if ($request->isMethod('get')) {
        $membership = Membership::find($request->membership);
        return view('users.services.forms.membership-checkout')->with([
          'user' => $user,
          'membership' => $membership,
          'close' => 'start',
          'route' => 'users.services.memberships',
          'success' => '/users/service/r/s-s/three',
          'back' => '/users/service/m-back',
        ]);
      }elseif($request->isMethod('post')){
        //  $membership = Membership::find($request->membership);
        //  foreach ($user->memberships as $m) {
        //    if ($m->role_id == $membership->role_id) {
        //      $user->memberships()->detach($m->id);
        //    }
        //  } #TODO: FIX THIS ROUTINE
        //  $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
        //  $user->notify(new ServiceProfile());
        //
        // $user->updateProfileRedirect('users.services.second-step.three','Servicio');
        return response(['success' => true]);
      }
    }
    public function registrationMembershipCheckoutUpdate(Request $request){
      $user = \Auth::user();
      if ($request->isMethod('get')) {
        $membership = Membership::find($request->membership);
        return view('users.services.forms.membership-checkout')->with([
          'user' => $user,
          'membership' => $membership,
          'route' => 'users.services.memberships.update',
          'close' => 'profile.service',
          'success' => '/users/profile/service#/order/',
          'back' => '/users/service/m-up-back',
        ]);
      }elseif($request->isMethod('post')){
        //  $membership = Membership::find($request->membership);
        //  foreach ($user->memberships as $m) {
        //    if ($m->role_id == $membership->role_id) {
        //      $user->memberships()->detach($m->id);
        //    }
        //  } #TODO: FIX THIS ROUTINE
        //  $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
        //  $user->notify(new ServiceProfile());
        //
        // $user->updateProfileRedirect('users.services.second-step.three','Servicio');
        return response(['success' => true]);
      }
    }
    public function registrationSecondStepThree(Request $request){

        $user = \Auth::user();
        $payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
          switch($payment->status){
            case 0:
                $payment->statusname = 'Anulada';
            break;
            case 1:
                $payment->statusname = 'Aprobada';
            break;
            case 2:
                $payment->statusname = 'Cancelada';
            break;
            default: 
                $payment->statusname = 'Inconclusa';
            break;
          }
        } else {
          $payment = null;
        }
        
        if ($request->isMethod('get')) {
            
            $company = Company::where('user_id', $user->id)->first();
            $photos = Photo::where('service_list_id', '>=', 1)->where('company_id', $company->id)->get();

            if (!is_null($photos)) {
                foreach($photos as $photo){
                $photo->delete();
                }
            }

            $user->updateProfileRedirect('users.services.second-step.three','Servicio');
            $service_memberships = Membership::getServiceMemberships();
            $user = \Auth::user();
            foreach ($service_memberships as $sm) {
                if ($sm->users->contains($user->id)) {
                  $features = json_decode($sm->features,true);
                  break;
                }
            }
            return view('users.services.forms.second-step-three')->with(
              [
                'user' => \Auth::user(),
                'services_list'=>ServiceList::all(),
                'services_type'=>ServiceType::all(),
                "main_services_limit" => $features["main_services"],
                "secondary_services_limit" => $features["secondary_services"],
                "payment" => $payment
              ]
            );

        }elseif($request->isMethod('post')){

            $company = $user->getServiceCompany();
            $company->servicesList()->sync($request->secondary_services);
            $company->description = $request->description;
            $company->save();
            $user->updateProfileRedirect('users.services.second-step.four','Servicio');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('users.services.second-step.four');
            }
        }
    }
    public function registrationSecondStepFour(Request $request){

        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $company = Company::where('user_id', $user->id)->first();
            $photos = Photo::where('service_list_id', '>=', 1)->where('company_id', $company->id)->get();

            if (!is_null($photos)) {
                foreach($photos as $photo){
                $photo->delete();
                }
            }

            $service_memberships = Membership::getServiceMemberships();
            $user = \Auth::user();
            $company_services = DB::table('companies')
              ->select('services_list.name','companies_has_services_list.service_list_id','companies_has_services_list.id')
              ->join('companies_has_services_list', 'companies_has_services_list.company_id', '=', 'companies.id')
              ->join('services_list', 'services_list.id', '=', 'companies_has_services_list.service_list_id')
              ->where('companies.user_id', $user->id)
              ->get();
            foreach ($service_memberships as $sm) {
      					if ($sm->users->contains($user->id)) {
      						$features = json_decode($sm->features,true);
      						break;
      					}
            }
            return view('users.services.forms.second-step-four')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'photo_limit' => $features['photos_per_project'], 'services' => $company_services ]);

        }elseif($request->isMethod('post')){
            $company = $user->getServiceCompany();
            $company->save();
            $user->updateProfileRedirect('users.services.second-step.five','Servicio');
            //$service
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect()->route('users.services.second-step.five');
            }
        }
    }
    public function registrationSecondStepFive(Request $request){

        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $service_memberships = Membership::getServiceMemberships();
            $user = \Auth::user();
            $company_services = DB::table('companies')
              ->select('services_list.name','companies_has_services_list.service_list_id','companies_has_services_list.id','companies_has_services_list.description')
              ->join('companies_has_services_list', 'companies_has_services_list.company_id', '=', 'companies.id')
              ->join('services_list', 'services_list.id', '=', 'companies_has_services_list.service_list_id')
              ->where('companies.user_id', $user->id)
              ->get();
            foreach ($service_memberships as $sm) {
                if ($sm->users->contains($user->id)) {
                  $features = json_decode($sm->features,true);
                  break;
                }
            }
            return view('users.services.forms.second-step-five')->with(['user' => \Auth::user(), 'services' => $company_services]);

        }elseif($request->isMethod('post')){

            $description = $request->description;
            $id = $request->id;

            foreach ($id as $key => $value) {
              DB::table('companies_has_services_list')
                ->where('id', $id[$key])
                ->update([
                  'description' => $description[$key]
                ]);
            }
            $user->updateProfileRedirect(null,'Servicio');

            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/profile/service');
            }
        }
    }
    public function getPhotos(Request $request){
      $user = \Auth::user();
      $services = DB::table('companies')
      ->select('services_list.name','companies_has_services_list.service_list_id','companies_has_services_list.id')
      ->join('companies_has_services_list', 'companies_has_services_list.company_id', '=', 'companies.id')
      ->join('services_list', 'services_list.id', '=', 'companies_has_services_list.service_list_id')
      ->where('companies.user_id', $user->id)
      ->get();

      foreach ($services as $key => $service){
        $photos = DB::table('photos')->where('cover', false)->where('service_list_id', $service->service_list_id)->get();
        $service->photos = $photos;
      }
      return response(['cover' => $user->getServiceCompany()->photos()->where(['cover' => true])->first(),
      'photos' => $user->getServiceCompany()->photos()->where(['cover' => false])->get(),
      'services' => $services]);
  	}
    public function savePhoto(Request $request){
  		$user = \Auth::user();
      $company = $user->getServiceCompany();
  		$files = $request->files->all();
  		foreach ($files as $file) {
  			try {
  				$db_file = Photo::find($request->photo_id);
  				if ($db_file) {
  					$db_file->delete();
  				}
  				$db_file = new Photo;
  				if ($request->cover) {
  					$old_cover = Photo::where(['company_id' => $request->company_id,'cover' => 1])->get();
  					foreach ($old_cover as $c) {
  						$c->delete();
  					}
  					$db_file->cover = true;
  				}
  				$date_uuid = Carbon::now()->format('Ymdhmsu');
  				$db_file->name = 'photo_'.$request->photo_name.'-'.$date_uuid. '.' . $file->getClientOriginalExtension();
  				\Storage::disk('local')->putFileAs('public/services/'.$company->id.'/photos/',$file,$db_file->name);
  				$db_file->company_id = $company->id;
  				$db_file->original_name = $file->getClientOriginalName();
          $db_file->path = '/storage/services/'.$company->id.'/photos/'.$db_file->name;
          $db_file->service_list_id = $request->service;

  				$db_file->save();
  			} catch (Exception $e) {
  			}
  		}
  		return response(['photo' => $db_file],200);
	  }

    public function getServicesList(Request $request){
      $user = \Auth::user();
      $services_list = $user->getServiceCompany()->servicesList->load("serviceType");
      $services_type = [];
      foreach (ServiceType::all() as $type) {
          foreach ($services_list as $serv) {
            if ($serv->service_type_id == $type->id) {
              $services_type[] = $type;
              break;
            }
          }
      }
      return response( [ "secondary_services" => $services_list, "primary_services" => $services_type, "all_secondary_services" => ServiceList::all()->load("serviceType"), "all_primary_services" => ServiceType::all() ] );
  	}

    public function getLogo(Request $request){
      return response(['logo' => Photo::where(['company_id' => $request->company_id, 'logo' => true])->get()]);
    }

    public function saveLogo(Request $request){
      $company = Company::find($request->company_id);        
  
          $files = $request->files->all();
  
          $db_file = new Photo;
      // return response($files);
      foreach ($files as $file) {            
        // code...
        // $file = $files[$request->photo_name];            
        try {
          if ($request->cover) {
            $old_cover = Photo::where(['company_id' => $request->company_id,'logo' => 1])->get();
            foreach ($old_cover as $l) {
              $l->delete();
              \Storage::disk('local')->delete('public/companies/'.$company->id.'/photos/'.$l->name);
            }
            $db_file->logo = true;
          }            
          $date_uuid = Carbon::now()->format('Ymdhmsu');
  
          $db_file->name = 'photo_'.$request->photo_name.'-'.$date_uuid. '.' . $file->getClientOriginalExtension();
          \Storage::disk('local')->putFileAs('public/companies/'.$company->id.'/photos/',$file,$db_file->name);
          $db_file->company_id = $company->id;
          $db_file->original_name = $file->getClientOriginalName();
          $db_file->path = '/storage/companies/'.$company->id.'/photos/'.$db_file->name;
  
          $db_file->save();
        } catch (Exception $e) {
        }
      }
      return response(["path" => $db_file->path, "id" => $db_file->id, "space_id" => $db_file->space_id]);
    }

    public function delLogo(Request $request){
      if($user = \Auth::user()){
        $company = Company::where('id',$user->getServiceCompany()->id)->where('user_id',$user->id)->first();
        if(isset($request->photo_id)){
          $photo = Photo::find($request->photo_id);
        }
        if(isset($request->filename)){
          $photo = Photo::where('original_name', $request->filename)->first();
        }
        
        if ( !is_null($photo) ) {
          $photo->delete();
          \Storage::disk('local')->delete('public/companies/'.$company->id.'/photos/'.$photo->name);
          return response( [ "operation" => true ] );
        }
        return response( [ "operation" => false ] );
      }
      
    }

    public function deletePhoto(Request $request){

      $photo = Photo::where('original_name', $request->filename)->where('block', null)->first();
      if (!is_null($photo) && !empty($request->filename) ) {
        $photo->delete();
        return response( [ "operation" => true] );
      }
      return response('Se elimino la imagen del dropzone',500);
    }
    public function deletePhotosService(Request $request){

      $photos = Photo::where('service_list_id', $request->service)->get();

      if (!is_null($photos) && !empty($request->service) ) {
        foreach($photos as $photo){
          $photo->delete();
        }
        return response([ 
          "operation" => true,
          "photos" => $photos
        ]);
      }
      return response( [
        "operation" => false,
        "photos" => $photos
      ] );
    }
    public function getPhotosService(Request $request){
      if($user = \Auth::user()){
        $photo = Photo::where('service_list_id', $request->service)->first();
        $photos = Photo::where('service_list_id', $request->service)->get();

        $blocks = Photo::where('service_list_id', $request->service)->get();

        foreach ($blocks as $block) {
          $block->block = 1;
          $block->save();
        }
          
        return response([ 
          "operation" => true,
          "photos" => $photos,
          "photo" => $photo
        ]);
      } else {
        return response([
          "operation" => false
        ]);
      }
      
    }
}
