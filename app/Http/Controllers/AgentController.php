<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Country, Commune, CivilStatus, Amenity, Membership, City, File, Property, PropertyType, PropertyFor, Company ,Photo};
use Carbon\Carbon;
use App\Notifications\AgentProfile;
use App\Payment;
use Illuminate\Support\Facades\DB;


class AgentController extends Controller
{
		public function __construct()
	  {

	  }

		public function registrationFirstStep(){
			return view('users.agents.forms.first-step')->with(['user' => \Auth::user()]);
	  }

    public function registrationFirstStepOne(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
        return view('users.agents.forms.first-step-one')->with(['user' => $user,
                                                                'countries' => Country::all(),
                                                                'civil_status' => CivilStatus::all()]);
        }elseif($request->isMethod('post')){

            $user->saveBasicData($request,false);
            $user->updateProfileRedirect('users.agents.first-step.two','Agente');
            File::generateFile($user, File::DICOM, File::FIN_FILES_TYPE);

            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/agent/r/first-step/two');
            }
        }

    }

	public function registrationFirstStepTwo(Request $request){
		$user = $request->userId ? User::find($request->userId) : \Auth()->user();
		if ($request->isMethod('get')) {
			return view('users.agents.forms.first-step-two')->with(['user' => \Auth::user(),
																   'cities' => Country::find(39)->cities]);
		}elseif($request->isMethod('post')){
			$request->userId = $user->id;
			$request->invoice = 0;
			$request->sii = 0;
			if (!is_null($user->getAgentCompany())) {
        		$company = $user->getAgentCompany();
			}else{
				$company = new Company();
			}
			$company->saveBasicData($request,false);
			$user->saveLocationData($request);
			$user->updateProfileRedirect('users.agents.first-step.three','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/first-step/three');
			}
		}
	}
	public function registrationFirstStepThree(Request $request){

		$user = $request->userId ? User::find($request->userId) : \Auth()->user();
		if ($request->isMethod('get')) {
			return view('users.agents.forms.first-step-three',['user' => \Auth::user()]);
		}elseif($request->isMethod('post')){
			if (!is_null($user->getAgentCompany())) {
        		$company = $user->getAgentCompany();
			}else{
				$company = new Company();
			}
			
			//$company->saveBasicData($request,false);

			$company->invoice = $request->invoice;
			$company->name = $request->name;
			$company->rut = $request->rut;
			$company->giro = $request->giro;
			$company->phone = $request->phone;
			$company->website = $request->website;
			$company->description = $request->description;

			$company->save();

			$files = $request->files->all();
            foreach ($files as $fileName => $file) {
                try {
                    $db_file = $user->files()->where('name', $fileName)->first();
                    if ($db_file) {
                        $path = 'users/'.$user->id.'/files/';
                        \Storage::disk('local')->putFileAs($path,$file,$fileName);
                        $db_file->original_name = $file->getClientOriginalName();
                        $db_file->path = $path.$fileName;
                        $db_file->save();
                    }
                } catch (Exception $e) {
                }
            }

			$user->updateProfileRedirect('users.agents.first-step.four','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/first-step/four');
			}
		}
	}

	public function registrationFirstStepFour(Request $request){

		$user = \Auth::user();

		if ($request->isMethod('get')) {
			return view('users.agents.forms.first-step-four')->with(['user' => \Auth::user(),'cities' => Country::find(39)->cities]);
		}elseif($request->isMethod('post')){
			$company = $user->getAgentCompany();
			$company->personal_address = $request->personal_address;

			if ($request->personal_address == 1) {
					$company->city_id = $user->city_id;
			    $company->address = $user->address;
			    $company->address_details = $user->address_details;
			    $company->latitude = $user->latitude;
			    $company->longitude = $user->longitude;
			}else{
					$company->city_id = $request->city;
			    $company->address = $request->address;
			    $company->address_details = $request->address_details;
			    $company->latitude = $request->latitude;
			    $company->longitude = $request->longitude;
			}

		  $company->save();

			$user->updateProfileRedirect('users.agents.memberships','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				 return redirect('/users/agent/memberships');
			}
		}
	}

	public function registrationMembershipsFormUpdate(Request $request){

		$user = \Auth::user();
		$payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
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
        } else {
            $payment = null;
        }

		$memberships = Membership::getAgentMemberships();

		return view('users.agents.forms.memberships')->with(['user' => \Auth::user(),
														'memberships' => $memberships,
														'payment' => $payment,
														'close' => 'profile.agent',
														'route' =>'users.agents.memberships-checkout.update',
														'back' => 'profile.agent']);
														
	}
	public function registrationMembershipsForm(Request $request){

		$user = \Auth::user();
		$payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
        if($payment){
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
        } else {
            $payment = null;
        }

		$memberships = Membership::getAgentMemberships();
		return view('users.agents.forms.memberships')->with(['user' => \Auth::user(),
														'memberships' => $memberships,
														'payment' => $payment,
														'close' => 'start',
														'route' =>'users.agents.memberships-checkout',
														'back' => 'users.agents.first-step.four'
														]);
														
	}
	public function registrationMembershipCheckout(Request $request){

		$user = \Auth::user();
		if ($request->isMethod('get')) {
			$membership = Membership::find($request->membership);
			return view('users.agents.forms.membership-checkout')->with([
				'user' => $user,
				'membership' => $membership,
				'route' => 'users.agents.memberships',
				'close' => 'start',
				'success' => '/users/agent/r/second-step/p/',
				'back' => '/users/agent/memberships/',
			]);
		}elseif($request->isMethod('post')){
			// $membership = Membership::find($request->membership);
			// foreach ($user->memberships as $m) {
			// 	if ($m->role_id == $membership->role_id) {
			// 		$user->memberships()->detach($m->id);
			// 	}
			// } #TODO: FIX THIS ROUTINE
			// $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
			// $user->notify(new AgentProfile());
            //
			//$user->updateProfileRedirect('users.agents.second-step','Agente');
			return response(['success' => true]);

		}
	}

	public function registrationMembershipCheckoutUpdate(Request $request){

		$user = \Auth::user();
		if ($request->isMethod('get')) {
			//$membership = Membership::find($request->membership);

			$membership = Membership::find($request->membership);

			return view('users.agents.forms.membership-checkout')->with([
				'user' => $user,
				'membership' => $membership,
				'route' => 'users.agents.memberships.update',
				'close' => 'profile.agent',
				'success' => '/users/profile/agent#/order/',
				'back' => '/users/agent/memberships-update',
			]);
		}elseif($request->isMethod('post')){
			// $membership = Membership::find($request->membership);
			// foreach ($user->memberships as $m) {
			// 	if ($m->role_id == $membership->role_id) {
			// 		$user->memberships()->detach($m->id);
			// 	}
			// } #TODO: FIX THIS ROUTINE
			// $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
			// $user->notify(new AgentProfile());
            //
			// $user->updateProfileRedirect('users.agents.second-step','Agente');
			return response(['success' => true]);

		}
	}

	public function registrationSecondStep(Request $request){
		$user = \Auth::user();
		$payment = Payment::where('order', $request->order)->where('user_id', $user->id)->first();
		$project = $user->getAgentCompany()->properties()->first();
        if($payment){
            switch($payment->status){
                case 0:
                    $payment->statusname = 'Anulada';
                break;
                case 1:
                    $payment->statusname = 'Aprobada';
                break;
                default: 
                    $payment->statusname = 'Inconclusa';
                break;
            }
        } else {
            $payment = null;
        }
		$user->updateProfileRedirect('users.agents.second-step','Agente');
		if ($request->isMethod('get')) {
			return view('users.agents.forms.second-step')->with(['user' => \Auth::user(), 'payment' => $payment, 'project' => $project]);
		} elseif ($request->isMethod('post')){
			if (is_null($project)) {
				$project = new Property();
			}
			
			$project->is_project = $request->is_project;
			$project->active = false;
			$project->company_id = $user->getAgentCompany()->id;
			$project->status = 4;
			$project->property_type_id = 1;
			$project->type_stay = 'LONG_STAY';
			$project->save();
			$user->updateProfileRedirect('users.agents.second-step.one','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				if($project->is_project == 0){
					return redirect('/users/agent/r/second-step/select');
				} else {
					return redirect('/users/agent/r/second-step/one');
				}
			}
		}
		

	}

	public function registrationSecondStepSelect(Request $request){
		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		if ($request->isMethod('get')) {
			return view('users.agents.forms.select1-step')->with([
				'user' => \Auth::user(),
				'property' => $project,
				]);
		}
		if ($request->isMethod('post')) {
			if($request->estadia == 'short'){
				$project->type_stay = 'SHORT_STAY';
			}
			if($request->estadia == 'long'){
				$project->type_stay = 'LONG_STAY';
			}
			$project->save();
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				return redirect('/users/agent/r/second-step/one/');
			}
		}
	}

	public function registrationSecondStepOne(Request $request){
		
		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();

		if($project->is_project == 1){
			$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
			$class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Habitación')->get();
		} else {
			$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
			if($project->type_stay == 'LONG_STAY'){
				$class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Terreno')->get();
			} else {
				$class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Terreno')->where("name", "<>", "Bodega")->get();
			}
		}

		if ($request->isMethod('get')) {
			$type = 1;
			return view('users.agents.forms.second-step-one')->with([
				'user' => $user,
				'property_type' => $class_type,
				'property' => $project,
				'type' => $type,
				'type_property' => null,
				'message1' => $message1
			]);

		}elseif($request->isMethod('post')){
			$project->property_type_id = $request->property_type;
			$project->save();
				
			//File::generatePropertyFiles($project);
			if ($project->users->count() == 0)  {
				$user->properties()->attach($project->id, ['type' => Property::TYPE_AGENT]);
			}

			$user->updateProfileRedirect('users.agents.second-step.one-one','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/second-step/one-one');
			}
		}
	}

	public function registrationSecondStepOneOne(Request $request){
		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		$type = 0;
		if ($request->isMethod('get')) {
			
			$type_project = PropertyType::FindOrFail($project->property_type_id);
			if($type_project->name == "Oficina" || $type_project->name == "Local Comercial"){
                $type_property = 1;
                
            }
            if($type_project->name == "Casa" || $type_project->name == "Departamento" || $type_project->name == "Habitación" || $type_project->name == "Estacionamiento" || $type_project->name == "Terreno" || $type_project->name == "Bodega"){
                $type_property = 0;
			}
			
			if($project->is_project == 1){
				$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
			} else {
				$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
			}
			return view('users.agents.forms.second-step-one')->with([
				'user' => $user,
				'property_type' => PropertyType::where("enabled",true)->get(),
				'property' => $project,
				'type' => $type,
				'type_property' => $type_property,
				'message1' => $message1
			]);

		}elseif($request->isMethod('post')){
			$project->name = substr($request->name, 0, 30);
			$project->condition = $request->condition;
			//$project->property_type_id = $request->property_type;
			$description = substr($request->description, 0, 800);
            $description = preg_replace("/\+?[0-9][0-9()\-\s+]{4,20}[0-9]/","*",$description);
			$project->description = preg_replace("/[^@\s]*@[^@\s]*\.[^@\s]*/","*", $description);
			$type = PropertyType::FindOrFail($project->property_type_id);
			if($project->is_project == 1){
				$project->builder = $request->builder;
				$project->architect = $request->architect;
				$available_date = str_replace('/', '-', $request->available_date);
				$project->available_date = date('Y-m-d', strtotime($available_date));
				switch ($type->name) {
					case 'Oficina':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					case 'Local Comercial':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					default:
						break;
				}
			} else {
				switch ($type->name) {
					case 'Oficina':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					case 'Local Comercial':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					default:
						break;
				}
			}
			
			
			$project->save();
				
			//File::generatePropertyFiles($project);

			$user->updateProfileRedirect('users.agents.second-step.two','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/second-step/two');
			}
		}
	}

	public function registrationSecondStepTwo(Request $request){

		$user = \Auth::user();
		
		$project = $user->getAgentCompany()->properties()->first();
		if ($request->isMethod('get')) {

			return view('users.agents.forms.second-step-two')->with(['user' => \Auth::user(),'property' => $project,'cities' => Country::find(39)->cities,'id' => 1]);
		}elseif($request->isMethod('post')){

			$project->city_id = $request->city;
			$project->address = $request->address;
			$project->address_details = $request->address_details;
			$project->latitude = $request->latitude;
			$project->longitude = $request->longitude;
			$project->save();
			$user->updateProfileRedirect('users.agents.second-step.three','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/second-step/three');
			}
		}
	}

	public function registrationSecondStepThree(Request $request){

		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();

	  if ($request->isMethod('get')) {

		// ANALIZAR -- REVISAR - 06-03-2019 ***********************************************

		$agent_memberships = $user->getAgentMerbership();
		
		$features = json_decode($agent_memberships->features,true);

		return view('users.agents.forms.second-step-three')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'property' => $project,'photo_limit' => $features['photos_per_project'] ]);
		//return view('users.agents.forms.second-step-three')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'property' => $project]);
	  }elseif($request->isMethod('post')){
		  /**
		  TO DO:
			  ¡make validation! Update check and so on~
		  *//*
		  $files = $request->files->all();
		  foreach ($files as $fileName => $file) {
			  try {
				  $db_file = $property->photos()->where('name', $fileName)->first();
				  if (!$db_file) {
					  $db_file = new Photo;
					  $db_file->name = 'photo_'.$fileName;
				  }
				  $date_uuid = Carbon::now()->format('Ymdhmsu');
				  \Storage::disk('local')->putFileAs('public/properties/'.$property->id.'/photos/',$file,$fileName.'-'.$date_uuid);
				  $db_file->property_id = $property->id;
				  $db_file->original_name = $file->getClientOriginalName();
				  $db_file->path = 'properties/'.$property->id.'/photos/'.$fileName.'-'.$date_uuid;
				  $db_file->save();
			  } catch (Exception $e) {
			  }
		  }*/
		  $user->updateProfileRedirect('users.agents.second-step.four','Agente');
		  if ($request->ajax()) {
			  return response(['success' => true]);
		  }else {
			  return redirect('/users/agent/r/second-step/four/');
			}
		}
	}
	  
	

	public function registrationSecondStepFour(Request $request){

		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}
		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento"){
			$type_property = 3;
		}
		if($type->name == "Terreno"){
			$type_property = 4;
		}
		if($type->name == "Bodega"){
			$type_property = 5;
		}
		if ($request->isMethod('get')) {
          return view('users.agents.forms.second-step-four')->with(['user' => \Auth::user(),'property' => $project, 'type_property' => $type_property ]);
        }elseif($request->isMethod('post')){
			
			if (isset($request->rent)) {
				$project->rent = str_replace(".","",$request->rent);
			}else{
				$project->rent = 0;
			}
			if (isset($request->rent_up)) {
				$project->rent_up = str_replace(".","",$request->rent_up);
			}else{
				$project->rent_up = 0;
			}
			if (isset($request->rent_year_1)) {
				$project->rent_year_1 = str_replace(".","",$request->rent_year_1);
			}else{
				$project->rent_year_1 = 0;
			}

			if (isset($request->rent_year_2)) {
				$project->rent_year_2 = str_replace(".","",$request->rent_year_2);
			}else{
				$project->rent_year_2 = 0;
			}

			if (isset($request->rent_year_3)) {
				$project->rent_year_3 = str_replace(".","",$request->rent_year_3);
			}else{
				$project->rent_year_3 = 0;
			}
			if (isset($request->common_expenses_limit)) {
				$project->common_expenses_limit = str_replace(".","",$request->common_expenses_limit);
			}else{
				$project->common_expenses_limit = 0;
			}
			if(isset($request->term_year)){
				$project->term_year = $request->term_year;
			} else {
				$project->term_year = 0;
			}
			if (isset($request->penalty_fees)) {
				$project->penalty_fees = str_replace(".","",$request->penalty_fees);
			}else{
				$project->penalty_fees = 0;
			}
			if (isset($request->warranty_ticket_price)) {
				$project->warranty_ticket_price = str_replace(".","",$request->warranty_ticket_price);
			}else{
				$project->warranty_ticket_price = 0;
			}
			if($project->is_project == 1){
				switch ($type_property) {
					case 1:
						$project->bathrooms = $request->bathrooms;
						$project->building_name = $request->building_name;
						$project->cellar = $request->cellar;
						$project->furnished = $request->furnished;
						$project->garden = $request->garden;
						$project->level = $request->level;
						$project->meeting_room = $request->meeting_room;
						$project->meters = $request->meters;
						$project->pool = $request->pool;
						$project->private_parking = $request->private_parking;
						$project->public_parking = $request->public_parking;
						$project->bedrooms = $request->rooms;
						$project->terrace = $request->terrace;
					 	break;
					case 3:
						$project->private_parking = $request->private_parking;
						$project->meters = $request->meters;
						$project->bedrooms = 0;
						$project->pet_preference = 'no';
						$project->smoking_allowed = 0;
						$project->furnished = 0;
						$project->propertiesFor()->sync(19);
						break;
					case 4:
						$project->meters = $request->meters;
						$project->lot_number = $request->lot_number;
						$project->private_parking = 0;
						$project->bedrooms = 0;
						$project->pet_preference = 'no';
						$project->smoking_allowed = 0;
						$project->furnished = 0;
						$project->propertiesFor()->sync(19);
						break;
					case 5:
						$project->cellar = $request->cellar;
						$project->cellar_description = $request->cellar_description;
						$project->meters =  $request->meters;
						$project->private_parking = 0;
						$project->bedrooms = 0;
						$project->pet_preference = 'no';
						$project->smoking_allowed = 0;
						$project->furnished = 0;
						$project->propertiesFor()->sync(19);
						break;
					
					default:
						$project->pool = $request->pool;
						$project->garden = $request->garden;
						$project->bedrooms = $request->bedrooms;
						$project->bathrooms = $request->bathrooms;
						$project->terrace = $request->terrace;
						$project->meters = $request->meters;
						$project->private_parking = $request->private_parking;
						$project->public_parking = $request->public_parking;
						$project->cellar = $request->cellar;
						//$project->cellar_description = $request->cellar_description;
						$project->furnished = $request->furnished;
						//$project->furnished_description = $request->furnished_description;
						break;
				}
			} else {
				if($type_property == 0){
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->collateral_require = $request->collateral_require;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->tenanting_months_quantity = $request->tenanting_months_quantity;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
					}
					$project->bathrooms = $request->bathrooms;
					$project->bedrooms = $request->bedrooms;
					$project->cellar = $request->cellar;
					$project->furnished = $request->furnished;
					$project->furnished_description = $request->furnished_description;
					$project->garden = $request->garden;
					$project->meters = $request->meters;
					$project->pool = $request->pool;
					$project->private_parking = $request->private_parking;
					$project->public_parking = $request->public_parking;
					$project->terrace = $request->terrace;
					
				} elseif ($type_property == 1) {
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->furnished = $request->furnished;
						$project->furnished_description = $request->furnished_description;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->penalty_fees = $request->penalty_fees;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
						$project->warranty_ticket = $request->warranty_ticket;
					}
					$project->bathrooms = $request->bathrooms;
					$project->building_name = $request->building_name;
					$project->cellar = $request->cellar;
					$project->cellar_description = $request->cellar_description;
					$project->exclusions = $request->exclusions;
					$project->level = $request->level;
					$project->meeting_room = $request->meeting_room;
					$project->meters = $request->meters;
					$project->private_parking = $request->private_parking;
					$project->public_parking = $request->public_parking;
					$project->bedrooms = $request->rooms;
				} elseif($type_property == 2) {
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->collateral_require = $request->collateral_require;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->tenanting_months_quantity = $request->tenanting_months_quantity;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
					}
					$project->bathrooms = $request->bathrooms;
					$project->bedrooms = 1;
					$project->cellar = $request->cellar;
					$project->furnished = $request->furnished;
					$project->furnished_description = $request->furnished_description;
					$project->meters = $request->meters;
					$project->terrace = $request->terrace;
					$project->private_parking = 0;
				}elseif ($type_property == 3){
					$project->meters = $request->meters;
                    $project->private_parking = $request->private_parking;
                    $project->private_parking = 0;
					$project->bedrooms = 0;
					$project->pet_preference = 'no';
					$project->smoking_allowed = 0;
					$project->furnished = 0;
					$project->propertiesFor()->sync(19);
				} elseif ($type_property == 5){
					$available_date = str_replace('/', '-', $request->available_date);
					$project->available_date = date('Y-m-d', strtotime($available_date));
					$project->cellar = $request->cellar;
					$project->cellar_description = $request->cellar_description;
					$project->collateral_require = $request->collateral_require;
					$project->meters = $request->meters;
					$project->months_advance_quantity = $request->months_advance_quantity;
					$project->tenanting_insurance = $request->tenanting_insurance;
					$project->tenanting_months_quantity = $request->tenanting_months_quantity;
					$project->warranty_months_quantity = $request->warranty_months_quantity;
					$project->private_parking = 0;
					$project->bedrooms = 1;
					$project->pet_preference = 'no';
					$project->smoking_allowed = 0;
					$project->furnished = 0;
					$project->propertiesFor()->sync(19);
				}
				
			}
			
			$project->save();
			
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				if($project->type_stay == 'LONG_STAY'){
					if($type_property == 3 || $type_property == 5){
						$user->updateProfileRedirect('users.agents.second-step.six','Agente');
						return redirect('/users/agent/r/second-step/six');
					} else {
						$user->updateProfileRedirect('users.agents.second-step.five','Agente');
						return redirect('/users/agent/r/second-step/five');
					}
					
				} elseif($project->type_stay == 'SHORT_STAY'){
					return redirect('/users/agent/r/second-step/four-one/');
				}
			}
        }
	}

	public function registrationSecondStepFourOne(Request $request){

		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}
		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento"){
			$type_property = 3;
		}

		if($request->isMethod('get')){
			return view('users.agents.forms.second-step-four-one')->with(['user' => \Auth::user(),'property' => $project ]);
		}elseif($request->isMethod('post')){
			$fechaql = str_replace('/', '-', $request->available_date);
			$w = new Carbon($fechaql);
			
			if (isset($request->rent)) {
				$project->rent = str_replace(".","",$request->rent);
			}else{
				$project->rent = 0;
			}
			if (isset($request->cleaning_rate)) {
				$project->cleaning_rate = str_replace(".","",$request->cleaning_rate);
			}else{
				$project->cleaning_rate = 0;
			}
			$available_date = str_replace('/', '-', $request->available_date);
			//$property->available_date = date('Y-m-d', strtotime($available_date));
			$project->available_date = $w->toDateString();
			$project->special_sale = intval($request->special_sale);
			$project->week_sale = intval( intval(explode(' ',$request->week_sale)[0]) );
			$project->month_sale = intval( intval(explode(' ',$request->month_sale)[0]) );
			$project->minimum_nights = intval( $request->minimum_nights);
			$project->checkin_hour = explode(' ',$request->checkin_hour)[1] == 'AM' ? intval($request->checkin_hour) : (intval($request->checkin_hour) + 12);
			$project->checkout_hour = explode(' ',$request->checkout_hour)[1] == 'AM' ? intval($request->checkout_hour) : (intval($request->checkout_hour) + 12);
			$project->save();
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				if($type_property == 3){
					$user->updateProfileRedirect('users.agents.second-step.six','Agente');
					return redirect('/users/agent/r/second-step/six/');
				} else {
					$user->updateProfileRedirect('users.agents.second-step.five','Agente');
					return redirect('/users/agent/r/second-step/five/');
				}
				
			}
		}
	}
	
	public function registrationSecondStepFive(Request $request){

		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}
		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento" || $type->name == "Bodega"){
			$type_property = 3;
		}
		if($type->name == "Terreno"){
			$type_property = 4;
		}

		if($type_property == 3){
			return redirect('/users/agent/r/second-step/four/');
		}
		if ($request->isMethod('get')) {
			return view('users.agents.forms.second-step-five')->with(['user' => \Auth::user(),'property_amenities' => Amenity::where('type', false)->get(),'property' => $project,'common_amenities' => Amenity::where('type', true)->get(), 'basic_services' => Amenity::where('type', '2')->get(), 'rules_amenities' => Amenity::where('type', '3')->get(), 'details_amenities' => Amenity::where('type', '4')->get(), 'possessions' => Amenity::where('type', '5')->get(), 'type_property' => $type_property]);
		}elseif($request->isMethod('post')){

			$project->amenities()->sync($request->amenities);
			$project->save();
			$user->updateProfileRedirect('users.agents.second-step.six','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/second-step/six');
			}
      	}
    }

	public function registrationSecondStepSix(Request $request){

		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}
		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento" || $type->name == "Terreno" || $type->name == "Bodega"){
			$type_property = 3;
		}
		if ($request->isMethod('get')) {
				return view('users.agents.forms.second-step-six')->with(['user' => \Auth::user(),'property' => $project,'id' => 1]);
		}elseif($request->isMethod('post')){
			if($project->type_stay == 'LONG_STAY'){
				$project->visit = $request->visit;
				if ($request->visit) {
					$project->schedule_dates = $request->schedule_dates;
				}else{
					$project->visit_from_date = null;
					$project->visit_to_date = null;
					$project->schedule_range = null;
					$project->schedule_dates = null;
				}
			}else {
				$project->schedule_dates = $request->schedule_dates;
			}
			
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				if($project->is_project == 0){
					if($type_property == 3){
						$project->active = true;
						$project->status = 0;
						$project->save();
						$user->updateProfileRedirect(null,'Agente');
						return redirect('/users/profile/agent');
					} else {
						$project->save();
						$user->updateProfileRedirect('users.agents.second-step.seven','Agente');
						return redirect('/users/agent/r/second-step/seven');
					}
				} else {
					if($type_property == 3){
						$project->active = true;
						$project->status = 0;
						$project->save();
						$user->updateProfileRedirect(null,'Agente');
						return redirect('/users/profile/agent');
					} else {
						$project->save();
						$user->updateProfileRedirect('users.agents.second-step.seven','Agente');
						return redirect('/users/agent/r/second-step/seven');
					}
					
				}
			}
	  	}
	}

	public function registrationSecondStepSeven(Request $request){
		$user = \Auth::user();
		$project = $user->getAgentCompany()->properties()->first();
		$property_type = PropertyType::find($project->property_type_id);
        switch ($property_type->name) {
            case 'Casa':
                $type = 0;
                break;
            case 'Departamento':
                $type = 0;
                break;
            case 'Habitación':
                $type = 0;
                break;
            case 'Local Comercial':
                $type = 1;
                break;
            case 'Oficina':
                $type = 1;
                break;
            default:
                $type = 0;
                break;
		}
		if ($request->isMethod('get')) {
			return view('users.agents.forms.second-step-seven')->with(['user' => \Auth::user(),'property' => $project,'properties_for' => PropertyFor::where('type', $type)->get(),'property_type' => $property_type, 'type' => $type]);
		} elseif ($request->isMethod('post')) {
			if($type == 0){
				if(isset($request->pet_preference)){
					$project->pet_preference = $request->pet_preference;
				} else {
					$project->pet_preference = 'no';
				}

				if(isset($request->smoking_allowed)){
					$project->smoking_allowed = $request->smoking_allowed;
				} else {
					$project->smoking_allowed = 0;
				}
			}
			//$property->nationals_with_rut = $request->nationals_with_rut;
			//$property->foreigners_with_rut = $request->foreigners_with_rut;
			//$property->foreigners_with_passport = $request->foreigners_with_passport;
			$project->propertiesFor()->sync($request->property_for);
			$user->updateProfileRedirect(null,'Agente');
			$project->active = true;
			$project->status = 0;
			$project->save();
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/profile/agent');
			}
		}
	}


	// PROJECTS *************

	// 0

	public function registrationThirdStep(Request $request){
		
		$user = \Auth::user();
		if(isset($request->id)){
			$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
			$project = Property::find($property[0]->property_id);
		} else {
			$project = null;
		}
		if ($request->isMethod('get')) {
			return view('users.agents.forms.third-step')->with(['user' => \Auth::user(),'property' => $project]);
		} elseif ($request->isMethod('post')){
			if (is_null($project)) {
				$project = new Property();
			}
			
			$project->is_project = $request->is_project;
			$project->active = false;
			$project->company_id = $user->getAgentCompany()->id;
			$project->status = 4;
			$project->redirect = '/users/agent/r/third-step/one/';
			$project->property_type_id = 1;
			$project->type_stay = 'LONG_STAY';
			$project->save();
			if ($project->users->count() == 0)  {
				$user->properties()->attach($project->id, ['type' => Property::TYPE_AGENT]);
			}
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				if($project->is_project == 1){
					return redirect('/users/agent/r/third-step/one/'.$project->id);
				} else {
					return redirect('/users/agent/r/third-step/select/'.$project->id);
				}
			}
		}

	}


	public function registrationThirdStepSelect(Request $request){
		$user = \Auth::user();
		if(isset($request->id)){
			$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
			$project = Property::find($property[0]->property_id);
		} else {
			$project = null;
		}
		if ($request->isMethod('get')) {
			return view('users.agents.forms.select2-step')->with([
				'user' => \Auth::user(),
				'property' => $project,
				]);
		}
		if ($request->isMethod('post')) {
			if($request->estadia == 'short'){
				$project->type_stay = 'SHORT_STAY';
			}
			if($request->estadia == 'long'){
				$project->type_stay = 'LONG_STAY';
			}
			$project->save();
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				return redirect('/users/agent/r/third-step/one/'.$project->id);
			}
		}
	}

	public function registrationThirdStepOne(Request $request){
		$user = \Auth::user();
		if(isset($request->id)){
			$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
			$project = Property::find($property[0]->property_id);
			$type_project = PropertyType::FindOrFail($project->property_type_id);
			if($type_project->name == "Oficina" || $type_project->name == "Local Comercial"){
				$type_property = 1;
				
			}
			if($type_project->name == "Casa" || $type_project->name == "Departamento" || $type_project->name == "Habitación" || $type_project->name == "Estacionamiento" || $type_project->name == "Bodega"){
				$type_property = 0;
			}
		} else {
			$project = null;
		}
		if($project->is_project == 1){
			$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
			$class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Habitación')->get();
		} else {
			$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
			if($project->type_stay == 'LONG_STAY'){
				$class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Terreno')->get();
			} else {
				$class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Terreno')->where("name", "<>", "Bodega")->get();
			}
		}
		
		if ($request->isMethod('get')) {
			$type = 1;
			return view('users.agents.forms.third-step-one')->with([
				'user' => \Auth::user(),			
				'property_type' => $class_type,
				'property' => $project,
				'type' => $type,
				'type_property' => $type_property,
				'message1' => $message1
				]);
			
		}elseif($request->isMethod('post')){

			$project->property_type_id = $request->property_type;
			$project->redirect = '/users/agent/r/third-step/one-one/';
			$project->save();
			//File::generatePropertyFiles($project);
			
			
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/third-step/one-one/'.$project->id);
			}

		}		

	}

	public function registrationThirdStepOneOne(Request $request){
		$user = \Auth::user();
		if(isset($request->id)){
			$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
			$project = Property::find($property[0]->property_id);
		} else {
			$project = null;
		}
		if($project->is_project == 1){
			$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
		} else {
			$message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
		}
		if ($request->isMethod('get')) {
			$type = 0;
			$type_project = PropertyType::FindOrFail($project->property_type_id);
			if($type_project->name == "Oficina" || $type_project->name == "Local Comercial"){
				$type_property = 1;
				
			}
			if($type_project->name == "Casa" || $type_project->name == "Departamento" || $type_project->name == "Habitación" || $type_project->name == "Estacionamiento" || $type_project->name == "Terreno" || $type_project->name == "Bodega"){
				$type_property = 0;
			}
			return view('users.agents.forms.third-step-one')->with(['user' => $user,
				'property_type' => PropertyType::where("enabled",true)->get(),
				'property' => $project,
				'type' => $type,
				'type_property' => $type_property,
				'message1' => $message1
			]);
			
		}elseif($request->isMethod('post')){
			$project->name = substr($request->name, 0, 30);
			$project->condition = $request->condition;
			//$project->property_type_id = $request->property_type;
			$description = substr($request->description, 0, 800);
            $description = preg_replace("/\+?[0-9][0-9()\-\s+]{4,20}[0-9]/","*",$description);
			$project->description = preg_replace("/[^@\s]*@[^@\s]*\.[^@\s]*/","*", $description);
			$project->redirect = '/users/agent/r/third-step/two/';
			$type = PropertyType::FindOrFail($project->property_type_id);
			if($project->is_project == 1){
				$project->builder = $request->builder;
				$project->architect = $request->architect;
				$available_date = str_replace('/', '-', $request->available_date);
				$project->available_date = date('Y-m-d', strtotime($available_date));
				switch ($type->name) {
					case 'Oficina':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					case 'Local Comercial':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					
					default:
						break;
				}
			} else {
				switch ($type->name) {
					case 'Oficina':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					case 'Local Comercial':
						$project->room_enablement = $request->room_enablement;
						$project->civil_work = $request->civil_work;
						$project->arquitecture_project = $request->arquitecture_project;
						$project->work_electric_water = $request->work_electric_water;
						break;
					
					default:
						break;
				}
			}
			$project->save();

			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/third-step/two/'.$project->id);
			}
		}
	}


	// public function registrationThirdStepTwo(Request $request, Property $property)
	// $project = Property::FindOrFail($request->property_id);
	// $project = $user->getAgentCompany()->projects()->latest()->take(1)->get();
	// property_type_id
	
	// 2

	public function registrationThirdStepTwo(Request $request){
		$user = \Auth::user();
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);
		//$project = $user->getAgentCompany()->properties()->latest()->take(1)->get();	
		//$project = $user->getAgentCompany()->projects()->latest()->take(1);
		//return $project;
		if ($request->isMethod('get')) {
			return view('users.agents.forms.third-step-two')->with([
				'user' => \Auth::user(),
				'property' => $project, 
				'cities' => Country::find(39)->cities,
				'id' => 1
				]);
		}
		// return $project;
		elseif($request->isMethod('post')){			
			$project->city_id = $request->city;
			$project->address = $request->address;
			$project->address_details = $request->address_details;
			$project->latitude = $request->latitude;
			$project->longitude = $request->longitude;
			$project->redirect = '/users/agent/r/third-step/three/';
			$project->save();
			//$user->updateProfileRedirect('users.agents.third-step.three','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/third-step/three/'.$project->id);
			}
		}

	}

	// 3

	public function registrationThirdStepThree(Request $request){

	$user = \Auth::user();
		// $project = $user->getAgentCompany()->projects()->first();
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);

	  if ($request->isMethod('get')) {
		$agent_memberships = $user->getAgentMerbership();
		
		$features = json_decode($agent_memberships->features,true);

		return view('users.agents.forms.third-step-three')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'property' => $project,'photo_limit' => $features['photos_per_project'] ]);
		
			//return view('users.agents.forms.third-step-three')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'property' => $project,'photo_limit']);
			//return view('users.agents.forms.third-step-three')->with(['user' => \Auth::user(),'id' => 1, 'photos' => null,'property' => $project]);
	  }elseif($request->isMethod('post')){
			$project->redirect = '/users/agent/r/third-step/four/';
			$project->save();
		  	//$user->updateProfileRedirect('users.agents.third-step.four','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			}else {
				return redirect('/users/agent/r/third-step/four/'.$project->id);
			}
	  }
	}
	
	// 4

	public function registrationThirdStepFour(Request $request){

		$user = \Auth::user();
		// $project = $user->getAgentCompany()->properties()->first();
		//$project = $request->id ? Property::find($request->id) : null;
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}
		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento"){
			$type_property = 3;
		}
		if($type->name == "Terreno"){
			$type_property = 4;
		}
		if($type->name == "Bodega"){
			$type_property = 5;
		}
		if ($request->isMethod('get')) {
			return view('users.agents.forms.third-step-four')->with([
				'user' => \Auth::user(),
				'property' => $project,
				'type_property' =>$type_property ]);
		}elseif($request->isMethod('post')){
			if (isset($request->rent)) {
				$project->rent = str_replace(".","",$request->rent);
			}else{
				$project->rent = 0;
			}
			if (isset($request->rent_up)) {
				$project->rent_up = str_replace(".","",$request->rent_up);
			}else{
				$project->rent_up = 0;
			}
			if (isset($request->rent_year_1)) {
				$project->rent_year_1 = str_replace(".","",$request->rent_year_1);
			}else{
				$project->rent_year_1 = 0;
			}

			if (isset($request->rent_year_2)) {
				$project->rent_year_2 = str_replace(".","",$request->rent_year_2);
			}else{
				$project->rent_year_2 = 0;
			}

			if (isset($request->rent_year_3)) {
				$project->rent_year_3 = str_replace(".","",$request->rent_year_3);
			}else{
				$project->rent_year_3 = 0;
			}
			if (isset($request->common_expenses_limit)) {
				$project->common_expenses_limit = str_replace(".","",$request->common_expenses_limit);
			}else{
				$project->common_expenses_limit = 0;
			}
			if(isset($request->term_year)){
				$project->term_year = $request->term_year;
			} else {
				$project->term_year = 0;
			}
			if (isset($request->penalty_fees)) {
				$project->penalty_fees = str_replace(".","",$request->penalty_fees);
			}else{
				$project->penalty_fees = 0;
			}
			if (isset($request->warranty_ticket_price)) {
				$project->warranty_ticket_price = str_replace(".","",$request->warranty_ticket_price);
			}else{
				$project->warranty_ticket_price = 0;
			}
			if($project->is_project == 1){
				switch ($type_property) {
					case 1:
						$project->bathrooms = $request->bathrooms;
						$project->building_name = $request->building_name;
						$project->cellar = $request->cellar;
						$project->furnished = $request->furnished;
						$project->garden = $request->garden;
						$project->level = $request->level;
						$project->meeting_room = $request->meeting_room;
						$project->meters = $request->meters;
						$project->pool = $request->pool;
						$project->private_parking = $request->private_parking;
						$project->public_parking = $request->public_parking;
						$project->bedrooms = $request->rooms;
						$project->terrace = $request->terrace;
					 	break;
					case 3:
						$project->private_parking = $request->private_parking;
						$project->meters = $request->meters;
						$project->bedrooms = 0;
						$project->pet_preference = 'no';
						$project->smoking_allowed = 0;
						$project->furnished = 0;
						$project->propertiesFor()->sync(19);
						break;
					case 4:
						$project->meters = $request->meters;
						$project->lot_number = $request->lot_number;
						$project->private_parking = 0;
						$project->bedrooms = 0;
						$project->pet_preference = 'no';
						$project->smoking_allowed = 0;
						$project->furnished = 0;
						$project->propertiesFor()->sync(19);
						break;
					case 5:
						$project->cellar = $request->cellar;
						$project->cellar_description = $request->cellar_description;
						$project->meters =  $request->meters;
						$project->private_parking = 0;
						$project->bedrooms = 0;
						$project->pet_preference = 'no';
						$project->smoking_allowed = 0;
						$project->furnished = 0;
						$project->propertiesFor()->sync(19);
						break;
					
					default:
						$project->pool = $request->pool;
						$project->garden = $request->garden;
						$project->bedrooms = $request->bedrooms;
						$project->bathrooms = $request->bathrooms;
						$project->terrace = $request->terrace;
						$project->meters = $request->meters;
						$project->private_parking = $request->private_parking;
						$project->public_parking = $request->public_parking;
						$project->cellar = $request->cellar;
						//$project->cellar_description = $request->cellar_description;
						$project->furnished = $request->furnished;
						//$project->furnished_description = $request->furnished_description;
						break;
				}
				
			} else {
				if($type_property == 0){
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->collateral_require = $request->collateral_require;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->tenanting_months_quantity = $request->tenanting_months_quantity;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
					} elseif ($project->type_stay == 'SHORT_STAY'){
						$project->single_beds = $request->single_beds;
						$project->double_beds = $request->double_beds;
						$project->number_furnished = $request->number_furnished;
					}
					$project->bathrooms = $request->bathrooms;
					$project->bedrooms = $request->bedrooms;
					$project->cellar = $request->cellar;
					$project->furnished = $request->furnished;
					$project->furnished_description = $request->furnished_description;
					$project->garden = $request->garden;
					$project->meters = $request->meters;
					$project->pool = $request->pool;
					$project->private_parking = $request->private_parking;
					$project->public_parking = $request->public_parking;
					$project->terrace = $request->terrace;
					
				} elseif ($type_property == 1) {
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->furnished = $request->furnished;
						$project->furnished_description = $request->furnished_description;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->penalty_fees = $request->penalty_fees;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
						$project->warranty_ticket = $request->warranty_ticket;
					}
					$project->bathrooms = $request->bathrooms;
					$project->building_name = $request->building_name;
					$project->cellar = $request->cellar;
					$project->cellar_description = $request->cellar_description;
					$project->exclusions = $request->exclusions;
					$project->level = $request->level;
					$project->meeting_room = $request->meeting_room;
					$project->meters = $request->meters;
					$project->private_parking = $request->private_parking;
					$project->public_parking = $request->public_parking;
					$project->bedrooms = $request->rooms;
					
				} elseif($type_property == 2) {
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->collateral_require = $request->collateral_require;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->tenanting_months_quantity = $request->tenanting_months_quantity;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
					} elseif ($project->type_stay == 'SHORT_STAY'){
						$project->single_beds = $request->single_beds;
						$project->double_beds = $request->double_beds;
						$project->number_furnished = $request->number_furnished;
					}
					$project->bathrooms = $request->bathrooms;
					$project->bedrooms = 1;
					$project->cellar = $request->cellar;
					$project->furnished = $request->furnished;
					$project->furnished_description = $request->furnished_description;
					$project->meters = $request->meters;
					$project->terrace = $request->terrace;
				} elseif ($type_property == 3){
					if($project->type_stay == 'LONG_STAY'){
						$available_date = str_replace('/', '-', $request->available_date);
						$project->available_date = date('Y-m-d', strtotime($available_date));
						$project->collateral_require = $request->collateral_require;
						$project->months_advance_quantity = $request->months_advance_quantity;
						$project->tenanting_insurance = $request->tenanting_insurance;
						$project->tenanting_months_quantity = $request->tenanting_months_quantity;
						$project->warranty_months_quantity = $request->warranty_months_quantity;
					}
					$project->meters = $request->meters;
					$project->private_parking = $request->private_parking;
					$project->bedrooms = 0;
					$project->pet_preference = 'no';
					$project->smoking_allowed = 0;
					$project->furnished = 0;
					$project->propertiesFor()->sync(19);
				} elseif ($type_property == 5){
					$available_date = str_replace('/', '-', $request->available_date);
					$project->available_date = date('Y-m-d', strtotime($available_date));
					$project->cellar = $request->cellar;
					$project->cellar_description = $request->cellar_description;
					$project->collateral_require = $request->collateral_require;
					$project->meters = $request->meters;
					$project->months_advance_quantity = $request->months_advance_quantity;
					$project->tenanting_insurance = $request->tenanting_insurance;
					$project->tenanting_months_quantity = $request->tenanting_months_quantity;
					$project->warranty_months_quantity = $request->warranty_months_quantity;
					$project->private_parking = 0;
					$project->bedrooms = 1;
					$project->pet_preference = 'no';
					$project->smoking_allowed = 0;
					$project->furnished = 0;
					$project->propertiesFor()->sync(19);
				}
			}
			$project->redirect = '/users/agent/r/third-step/five/';
			
			$project->save();

			//$user->updateProfileRedirect('users.agents.third-step.five','Agente');
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				if($project->type_stay == 'LONG_STAY'){
					if($type_property == 3 || $type_property == 5){
						return redirect('/users/agent/r/third-step/six/'.$project->id);
					} else {
						return redirect('/users/agent/r/third-step/five/'.$project->id);
					}
				} elseif ($project->type_stay == 'SHORT_STAY'){
					return redirect('/users/agent/r/third-step/four-one/'.$project->id);
				}
			}
		}
	}

	public function registrationThirdStepFourOne(Request $request){

		$user = \Auth::user();
		// $project = $user->getAgentCompany()->properties()->first();
		//$project = $request->id ? Property::find($request->id) : null;
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}

		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento"){
			$type_property = 3;
		}

		if($request->isMethod('get')){
			return view('users.agents.forms.third-step-four-one')->with(['user' => \Auth::user(),'property' => $project ]);
		}elseif($request->isMethod('post')){
			
			$fechaql = str_replace('/', '-', $request->available_date);
			$w = new Carbon($fechaql);
			
			if (isset($request->rent)) {
				$project->rent = str_replace(".","",$request->rent);
			}else{
				$project->rent = 0;
			}
			if (isset($request->cleaning_rate)) {
				$project->cleaning_rate = str_replace(".","",$request->cleaning_rate);
			}else{
				$project->cleaning_rate = 0;
			}
			$available_date = str_replace('/', '-', $request->available_date);
			//$property->available_date = date('Y-m-d', strtotime($available_date));
			$project->available_date = $w->toDateString();
			$project->special_sale = intval($request->special_sale);
			$project->week_sale = intval( intval(explode(' ',$request->week_sale)[0]) );
			$project->month_sale = intval( intval(explode(' ',$request->month_sale)[0]) );
			$project->minimum_nights = intval( $request->minimum_nights);
			$project->checkin_hour = explode(' ',$request->checkin_hour)[1] == 'AM' ? intval($request->checkin_hour) : (intval($request->checkin_hour) + 12);
			$project->checkout_hour = explode(' ',$request->checkout_hour)[1] == 'AM' ? intval($request->checkin_hour) : (intval($request->checkin_hour) + 12);
			$project->save();
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				if($type_property == 3){
					return redirect('/users/agent/r/third-step/six/'.$project->id);
				} else {
					return redirect('/users/agent/r/third-step/five/'.$project->id);
				}
				
			}
		}
	}

	// 5

	public function registrationThirdStepFive(Request $request){
	
		$user = \Auth::user();
		// $project = $user->getAgentCompany()->properties()->first();
		//$project = $request->id ? Property::find($request->id) : null;
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}

		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento" || $type->name == "Bodega"){
			$type_property = 3;
		}
		if($type->name == "Terreno"){
			$type_property = 4;
		}

		if($type_property == 3){
			return redirect('/users/agent/r/third-step/four/'.$project->id);
		}
			if ($request->isMethod('get')) {
				return view('users.agents.forms.third-step-five')->with(['user' => \Auth::user(),'property_amenities' => Amenity::where('type', false)->get(),'property' => $project,'common_amenities' => Amenity::where('type', true)->get(), 'basic_services' => Amenity::where('type', '2')->get(), 'rules_amenities' => Amenity::where('type', '3')->get(), 'details_amenities' => Amenity::where('type', '4')->get(), 'possessions' => Amenity::where('type', '5')->get(), 'type_property' => $type_property]);
			} elseif($request->isMethod('post')) {

				$project->amenities()->sync($request->amenities);
				$project->redirect = '/users/agent/r/third-step/six/';
				$project->save();
				//$user->updateProfileRedirect('users.agents.third-step.six','Agente');
				if ($request->ajax()) {
					return response(['success' => true]);
				} else {
					return redirect('/users/agent/r/third-step/six/'.$project->id);
				}
			}
		}
		
	// 6

	public function registrationThirdStepSix(Request $request){

		$user = \Auth::user();
		// $project = $user->getAgentCompany()->properties()->first();
		//$project = $request->id ? Property::find($request->id) : null;
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);
		$type = PropertyType::find($project->property_type_id);
		if($type->name == "Oficina" || $type->name == "Local Comercial"){
			$type_property = 1;
			
		}
		if($type->name == "Casa" || $type->name == "Departamento"){
			$type_property = 0;
		}

		if($type->name == "Habitación"){
			$type_property = 2;
		}
		if($type->name == "Estacionamiento" || $type->name == "Terreno" || $type->name == "Bodega"){
			$type_property = 3;
		}

		if ($request->isMethod('get')) {
				return view('users.agents.forms.third-step-six')->with(['user' => \Auth::user(),'property' => $project,'id' => 1]);
		}elseif($request->isMethod('post')){
			$project->visit = $request->visit;
			if($project->type_stay == 'LONG_STAY'){
				if ($request->visit) {
					$project->schedule_dates = $request->schedule_dates;
				}else{
					$project->visit_from_date = null;
					$project->visit_to_date = null;
					$project->schedule_range = null;
					$project->schedule_dates = null;
				}
			} else {
                $project->schedule_dates = $request->schedule_dates;
            }
			
			
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				if($project->is_project == 0){
					
					if($type_property == 3 || $type_property == 4 ){
						$project->active = true;
						$project->status = 0;
						$project->redirect = null;
						$project->save();
						return redirect('/users/profile/agent');
					} else {
						$project->redirect = '/users/agent/r/third-step/seven';
						$project->save();
						return redirect('/users/agent/r/third-step/seven/'.$project->id);
					}
					
				} else {
					if($type_property == 3 || $type_property == 4){
						$project->active = true;
						$project->status = 0;
						$project->redirect = null;
						$project->save();
						return redirect('/users/profile/agent');
					} else {
						$project->redirect = '/users/agent/r/third-step/seven';
						$project->save();
						return redirect('/users/agent/r/third-step/seven/'.$project->id);
					}
				}
			}
		}
	}

	public function registrationThirdStepSeven(Request $request){
		$user = \Auth::user();
		$property = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',5)->orderBy('id','DESC')->take(1)->get();
		$project = Property::find($property[0]->property_id);
		$property_type = PropertyType::find($project->property_type_id);
        switch ($property_type->name) {
            case 'Casa':
                $type = 0;
                break;
            case 'Departamento':
                $type = 0;
                break;
            case 'Habitación':
                $type = 0;
                break;
            case 'Local Comercial':
                $type = 1;
                break;
            case 'Oficina':
                $type = 1;
                break;
            default:
                $type = 0;
                break;
		}
		if ($request->isMethod('get')) {
			return view('users.agents.forms.third-step-seven')->with(['user' => \Auth::user(),'property' => $project,'properties_for' => PropertyFor::where('type', $type)->get(),'property_type' => $property_type, 'type' => $type]);
	  	}elseif($request->isMethod('post')){
			if($type == 0){
				if(isset($request->pet_preference)){
					$project->pet_preference = $request->pet_preference;
				} else {
					$project->pet_preference = 'no';
				}

				if(isset($request->smoking_allowed)){
					$project->smoking_allowed = $request->smoking_allowed;
				} else {
					$project->smoking_allowed = 0;
				}
			}
			//$property->nationals_with_rut = $request->nationals_with_rut;
			//$property->foreigners_with_rut = $request->foreigners_with_rut;
			//$property->foreigners_with_passport = $request->foreigners_with_passport;
			$project->propertiesFor()->sync($request->property_for);
			$project->active = true;
			$project->status = 0;
			$project->redirect = null;
			$project->save();
			if ($request->ajax()) {
				return response(['success' => true]);
			} else {
				return redirect('/users/profile/agent');
			}
		}
	}

	public function getPhotos(Request $request){
		return response(['cover' => Photo::where(['property_id' => $request->property_id, 'cover' => true])->first(),
		'photos' => Photo::where(['property_id' => $request->property_id, 'cover' => false])->get()]);
	}
	public function deletePhoto(Request $request){

		$photo = Photo::find($request->photo_id);
		if ( !is_null($photo) ) {
			$photo->delete();
			return response( [ "operation" => true ] );
		}
		return response( [ "operation" => false ] );
	}
	public function savePhoto(Request $request){
		$property = Property::find($request->property_id);
		$files = $request->files->all();
		// return response($files);
		foreach ($files as $file) {
			// code...
			// $file = $files[$request->photo_name];
			try {
				$db_file = Photo::find($request->photo_id);
				if ($db_file) {
					$db_file->delete();
				}
				$db_file = new Photo;
				if ($request->cover) {
					$old_cover = Photo::where(['property_id' => $request->property_id,'cover' => 1])->get();
					foreach ($old_cover as $c) {
						$c->delete();
					}
					$db_file->cover = true;
				}
				if ($request->space_id == 0) {
					$db_file->space_id = null;
				}else{
					$db_file->space_id = $request->space_id;
				}
				$date_uuid = Carbon::now()->format('Ymdhmsu');
				$db_file->name = 'photo_'.$request->photo_name.'-'.$date_uuid. '.' . $file->getClientOriginalExtension();
				\Storage::disk('local')->putFileAs('public/projects/'.$property->id.'/photos/',$file,$db_file->name);
				$db_file->property_id = $property->id;
				$db_file->original_name = $file->getClientOriginalName();
				$db_file->path = '/storage/projects/'.$property->id.'/photos/'.$db_file->name;

				$db_file->save();
			} catch (Exception $e) {
			}
		}
		return response(["path" => $db_file->path, "id" => $db_file->id, "space_id" => $db_file->space_id]);
	}
	public function changeSpacePhoto(Request $request){
		$photo = Photo::where([ 'id' => $request->photo_id ])->first();
		if ( !is_null($photo) ) {
			$photo->space_id = $request->space_id;
			$photo->save();
			return response(["path" => $photo->path, "photo_id" => $photo->id]);
		}
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
					
				}            
				$date_uuid = Carbon::now()->format('Ymdhmsu');

				$db_file->name = 'photo_'.$request->photo_name.'-'.$date_uuid. '.' . $file->getClientOriginalExtension();
				\Storage::disk('local')->putFileAs('public/companies/'.$company->id.'/photos/',$file,$db_file->name);
				$db_file->company_id = $company->id;
				$db_file->original_name = $file->getClientOriginalName();
				$db_file->path = '/storage/companies/'.$company->id.'/photos/'.$db_file->name;
				$db_file->logo = true;
				$db_file->save();
			} catch (Exception $e) {
			}
		}
		return response(["path" => $db_file->path, "id" => $db_file->id, "space_id" => $db_file->space_id]);
	}

	public function delLogo(Request $request){
		if($user = \Auth::user()){
			$company = Company::where('id',$user->getAgentCompany()->id)->where('user_id',$user->id)->first();
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



}

