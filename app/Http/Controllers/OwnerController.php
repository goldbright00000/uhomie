<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\{User, Country, Commune, CivilStatus, City, Membership, Property,PropertyType,File,Amenity,PropertyFor, Photo, Space, Bank};
use App\Notifications\{OwnerProfile};
use App\Payment;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    //
    public function registrationFirstStep(){
		    return view('users.owners.forms.first-step')->with(['user' => \Auth::user()]);
    }

    public function registrationFirstStepOne(Request $request){
        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.owners.forms.first-step-one')->with(['user' => \Auth::user(),'countries' => Country::all(),'civil_status' => CivilStatus::all()]);
        }elseif($request->isMethod('post')){
            $user->saveBasicData($request);
            $user->updateProfileRedirect('users.owners.first-step.two','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/owner/registration/first-step/two');
            }
        }
    }
    public function registrationFirstStepTwo(Request $request){

        $user = $request->userId ? User::find($request->userId) : \Auth()->user();
        if ($request->isMethod('get')) {
            return view('users.owners.forms.first-step-two')->with(['user' => \Auth::user(),'cities' => Country::find(39)->cities]);
        }elseif($request->isMethod('post')){
            $user->saveLocationData($request);
            $user->updateProfileRedirect('users.owners.forms.second-step','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/owner/registration/second-step');
            }
        }
    }
    
    public function registrationSecondStep(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        if($request->isMethod('get')) {
            return view('users.owners.forms.second-step')->with(['user' => \Auth::user(), 'property' => $property]);
        } elseif($request->isMethod('post')){
            if (is_null($property)) {
                $property = new Property();
            }
            $property->is_project = $request->is_project;
            $property->property_type_id = 1;
            $property->active = false;
            $property->status = 4;
            $property->type_stay = 'LONG_STAY';

            $property->save();
            if ($property->users->count() == 0)  {
                $user->properties()->attach($property->id, ['type' => Property::TYPE_OWNER]);
            }
            
            if($property->is_project == 0){
                $user->updateProfileRedirect('users.owners.second-step.select','Propietario');
                return redirect('/users/owner/registration/second-step/select');
            } elseif($property->is_project == 1){
                $user->updateProfileRedirect('users.owners.second-step.one','Propietario');
                return redirect('/users/owner/registration/second-step/one');
            }

        }
    }
    public function registrationSelect(Request $request)
    {
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        if( $request->isMethod('get') ){
            if($property == null){
                return redirect('/users/owner/registration/second-step');
            }
            return view('users.owners.forms.second-step-select')->with(['user' => \Auth::user()]);
        }elseif( $request->isMethod('post') ){
            if( $request->estadia == 'short' ){
                $property->type_stay = 'SHORT_STAY';
            } else {
                $property->type_stay = 'LONG_STAY';
            }
            $property->save();
            $user->updateProfileRedirect('users.owners.forms.second-step.one','Propietario');
            return redirect('/users/owner/registration/second-step/one');
        }
    }
    public function registrationSecondStepOne(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $type = PropertyType::FindOrFail($property->property_type_id);
        if($type->name == "Oficina" || $type->name == "Local Comercial"){
            $type_property = 1;
            
        }
        if($type->name == "Casa" || $type->name == "Departamento" || $type->name == "Habitación" || $type->name == "Estacionamiento" || $type->name == "Bodega"){
            $type_property = 0;
        }
        if($property->is_project == 1){
            $message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
            $class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Habitación')->get();
        } else {
            $message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
            if($property->type_stay == 'LONG_STAY'){
                $class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Terreno')->get();
            } else {
                $class_type = PropertyType::where("enabled",true)->where("name", '<>', 'Terreno')->where("name", "<>", "Bodega")->get();
            }
        }
        if ($request->isMethod('get')) {
            return view('users.owners.forms.second-step-one')->with([
                'user' => \Auth::user(),
                'property' => $property,
                'property_type' => $class_type,
                'type' => true,
                'type_property' => $type_property,
                'message1' => $message1]);
        }elseif($request->isMethod('post')){
            $property->property_type_id = $request->property_type;
            $property->status = 4;
            $property->active = false;
            $property->redirect = '/properties/registration/first-step/two/';
            $property->save();
            $type = PropertyType::FindOrFail($property->property_type_id);
            switch ($type) {
                case 'Local Comercial':
                    File::generateOfficeFiles($property);
                    break;
                case 'Oficina':
                    File::generateOfficeFiles($property);
                    break;
                case 'Casa':
                    File::generatePropertyFiles($property);
                    break;
                case 'Departamento':
                    File::generatePropertyFiles($property);
                    break;
                case 'Habitación':
                    File::generateRoomFiles($property);
                    break;
                default:
                    
                    break;
            }
            $user->updateProfileRedirect('users.owners.second-step.one-one','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/owner/registration/second-step/one-one');
            }
        }
    }
    public function registrationSecondStepOneOne(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        if ($request->isMethod('get')) {
            if($property->is_project == 1){
                $message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
            } else {
                $message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
            }
            $type = PropertyType::FindOrFail($property->property_type_id);
            if($type->name == "Oficina" || $type->name == "Local Comercial" || $type->name == "Bodega"){
                $type_property = 1;
                
            }
            if($type->name == "Casa" || $type->name == "Departamento" || $type->name == "Habitación" || $type->name == "Estacionamiento" || $type->name == "Terreno" || $type->name == "Bodega"){
                $type_property = 0;
            }
            return view('users.owners.forms.second-step-one')->with([
                'user' => \Auth::user(),
                'property' => $property,
                'property_type' => PropertyType::where("enabled",true)->get(),
                'type' => 0,
                'type_property' => $type_property,
                'message1' => $message1]);
        }elseif($request->isMethod('post')){
            $property->name = substr($request->name, 0, 30);
			$property->condition = $request->condition;
			$description = substr($request->description, 0, 800);
            $description = preg_replace("/\+?[0-9][0-9()\-\s+]{4,20}[0-9]/","*",$description);
			$property->description = preg_replace("/[^@\s]*@[^@\s]*\.[^@\s]*/","*", $description);
			$property->redirect = '/users/agent/r/third-step/two/';
			$type = PropertyType::FindOrFail($property->property_type_id);
			if($property->is_project == 1){
				$property->builder = $request->builder;
				$property->architect = $request->architect;
				$available_date = str_replace('/', '-', $request->available_date);
				$property->available_date = date('Y-m-d', strtotime($available_date));
				switch ($type->name) {
					case 'Oficina':
						$property->room_enablement = $request->room_enablement;
						$property->civil_work = $request->civil_work;
						$property->arquitecture_project = $request->arquitecture_project;
						$property->work_electric_water = $request->work_electric_water;
						break;
					case 'Local Comercial':
						$property->room_enablement = $request->room_enablement;
						$property->civil_work = $request->civil_work;
						$property->arquitecture_project = $request->arquitecture_project;
						$property->work_electric_water = $request->work_electric_water;
						break;
					
					default:
						break;
				}
			} else {
				switch ($type->name) {
					case 'Oficina':
						$property->room_enablement = $request->room_enablement;
						$property->civil_work = $request->civil_work;
						$property->arquitecture_project = $request->arquitecture_project;
						$property->work_electric_water = $request->work_electric_water;
						break;
					case 'Local Comercial':
						$property->room_enablement = $request->room_enablement;
						$property->civil_work = $request->civil_work;
						$property->arquitecture_project = $request->arquitecture_project;
						$property->work_electric_water = $request->work_electric_water;
						break;
					
					default:
						break;
				}
            }
			$property->save();
            $user->updateProfileRedirect('users.owners.second-step.two','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/owner/registration/second-step/two');
            }
        }
    }
    public function registrationSecondStepTwo(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        if ($request->isMethod('get')) {
            return view('users.owners.forms.second-step-two')->with(['user' => \Auth::user(),'property' => $property,'cities' => Country::find(39)->cities]);

        }elseif($request->isMethod('post')){

            $property->city_id = $request->city;
            $property->address = $request->address;
            $property->address_details = $request->address_details;
            $property->latitude = $request->latitude;
            $property->longitude = $request->longitude;
            $property->save();
            $user->updateProfileRedirect('users.owners.second-step.three','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            } else {
                return redirect('/users/owner/registration/second-step/three');
            }
        }
    }
    public function registrationSecondStepThree(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        #dd( $property->photos);
        if ($request->isMethod('get')) {
          return view('users.owners.forms.second-step-three')->with(['user' => \Auth::user(),'property' => $property,
          'spaces' => Space::all()]);
        }elseif($request->isMethod('post')){
            $user->updateProfileRedirect('users.owners.second-step.four','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/owner/registration/second-step/four');
            }
        }
    }
    public function registrationSecondStepFour(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);
        $type = PropertyType::find($property->property_type_id);
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

            return view('properties.forms.first-step-four')->with([
                'user' => \Auth::user(),
                'property' => $property,
                'type_property' => $type_property]);

        }elseif($request->isMethod('post')){
            if($property->is_project == 1){
				switch ($type_property) {
                    case 1:
						$property->bathrooms = $request->bathrooms;
						$property->building_name = $request->building_name;
						$property->cellar = $request->cellar;
						$property->furnished = $request->furnished;
						$property->garden = $request->garden;
						$property->level = $request->level;
						$property->meeting_room = $request->meeting_room;
						$property->meters = $request->meters;
						$property->pool = $request->pool;
						$property->private_parking = $request->private_parking;
						$property->public_parking = $request->public_parking;
						$property->bedrooms = $request->rooms;
						$property->terrace = $request->terrace;
					 	break;
					case 3:
						$property->private_parking = $request->private_parking;
                        $property->meters = $request->meters;
                        $property->private_parking = 0;
                        $property->bedrooms = 0;
                        $property->pet_preference = 'no';
                        $property->smoking_allowed = 0;
                        $property->furnished = 0;
						break;
					case 4:
						$property->meters = $request->meters;
                        $property->lot_number = $request->lot_number;
                        $property->private_parking = 0;
                        $property->bedrooms = 0;
                        $property->pet_preference = 'no';
                        $property->smoking_allowed = 0;
                        $property->furnished = 0;
						break;
					case 5:
						$property->cellar = $request->cellar;
						$property->cellar_description = $request->cellar_description;
                        $property->meters =  $request->meters;
                        $property->private_parking = 0;
                        $property->bedrooms = 0;
                        $property->pet_preference = 'no';
                        $property->smoking_allowed = 0;
                        $property->furnished = 0;
						break;
					
					default:
						$property->pool = $request->pool;
						$property->garden = $request->garden;
						$property->bedrooms = $request->bedrooms;
						$property->bathrooms = $request->bathrooms;
						$property->terrace = $request->terrace;
						$property->meters = $request->meters;
						$property->private_parking = $request->private_parking;
						$property->public_parking = $request->public_parking;
						$property->cellar = $request->cellar;
						//$project->cellar_description = $request->cellar_description;
						$property->furnished = $request->furnished;
						//$project->furnished_description = $request->furnished_description;
						break;
				}
				
			} else {
				if($type_property == 0){
					if ($property->type_stay == 'SHORT_STAY'){
						$property->single_beds = $request->single_beds;
						$property->double_beds = $request->double_beds;
						$property->number_furnished = $request->number_furnished;
					}
					$property->bathrooms = $request->bathrooms;
					$property->bedrooms = $request->bedrooms;
					$property->cellar = $request->cellar;
                    $property->furnished = $request->furnished;
                    $property->number_furnished = $request->number_furnished;
					$property->furnished_description = $request->furnished_description;
					$property->garden = $request->garden;
					$property->meters = $request->meters;
					$property->pool = $request->pool;
					$property->private_parking = $request->private_parking;
					$property->public_parking = $request->public_parking;
					$property->terrace = $request->terrace;
					
				} elseif ($type_property == 1) {
					$property->bathrooms = $request->bathrooms;
					$property->building_name = $request->building_name;
					$property->cellar = $request->cellar;
					$property->cellar_description = $request->cellar_description;
					$property->exclusions = $request->exclusions;
					$property->level = $request->level;
					$property->meeting_room = $request->meeting_room;
					$property->meters = $request->meters;
					$property->private_parking = $request->private_parking;
					$property->public_parking = $request->public_parking;
					$property->bedrooms = $request->rooms;
					
				} elseif($type_property == 2) {
					if ($property->type_stay == 'SHORT_STAY'){
						$property->single_beds = $request->single_beds;
						$property->double_beds = $request->double_beds;
						$property->number_furnished = $request->number_furnished;
					}
					$property->bathrooms = $request->bathrooms;
					$property->bedrooms = 1;
					$property->cellar = $request->cellar;
					$property->furnished = $request->furnished;
					$property->furnished_description = $request->furnished_description;
					$property->meters = $request->meters;
					$property->terrace = $request->terrace;
				} elseif ($type_property == 3){
					$property->meters = $request->meters;
                    $property->private_parking = $request->private_parking;
					$property->bedrooms = 0;
					$property->pet_preference = 'no';
					$property->smoking_allowed = 0;
					$property->furnished = 0;
				} elseif ($type_property == 5){
					$property->cellar = $request->cellar;
					$property->cellar_description = $request->cellar_description;
					$property->meters = $request->meters;
                    $property->private_parking = 0;
					$property->bedrooms = 1;
					$property->pet_preference = 'no';
					$property->smoking_allowed = 0;
					$property->furnished = 0;
				}
			}
            $property->save();
            if ($request->ajax()) {
                return response(['success' => true]);
            } else {
                if($type_property == 3 || $type_property == 4 || $type_property == 5){
                    $user->updateProfileRedirect('users.owners.third-step','Propietario');
                    return redirect('/users/owner/registration/third-step');
                } else {
                    $user->updateProfileRedirect('users.owners.second-step.five','Propietario');
                    return redirect('/users/owner/registration/second-step/five');
                }
            }
        }
    }
    public function registrationSecondStepFive(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $type = PropertyType::find($property->property_type_id);
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
        if ($request->isMethod('get')) {
            return view('users.owners.forms.second-step-five')->with(['user' => \Auth::user(),'property_amenities' => Amenity::where('type', false)->get(),'property' => $property,'common_amenities' => Amenity::where('type', true)->get(), 'basic_services' => Amenity::where('type', '2')->get(), 'rules_amenities' => Amenity::where('type', '3')->get(), 'details_amenities' => Amenity::where('type', '4')->get(), 'possessions' => Amenity::where('type', '5')->get(), 'type_property' => $type_property]);
        }elseif($request->isMethod('post')){
            $property->amenities()->sync($request->amenities);
            $property->save();
            $user->updateProfileRedirect('users.owners.third-step','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/users/owner/registration/third-step');
            }
        }
    }
    public function registrationThirdStep(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);
        switch ($property_type->name) {
            case 'Estacionamiento':
                $back = 'users.owners.second-step.four';
                break;
            case 'Bodega':
                $back = 'users.owners.second-step.four';
                break;
            case 'Terreno':
                $back = 'users.owners.second-step.four';
                break;
            
            default:
                $back = 'users.owners.second-step.five';
                break;
        }
        if($property->is_project == 1){
            $message1 = 'CONDICIONES DE VENTA';
            $message2 = 'Todos los datos que configures te ayudaran a destacar tu propiedad, aumentar las probabilidades de venta en menos tiempo y encontrar tu comprador ideal que califique a tus criterios.';
        } else {
            $message1 = 'CONDICIONES DE ARRIENDO';
            $message2 = 'Todos los datos que configures te ayudaran a destacar tu propiedad, aumentar las probabilidades de arriendo en menos tiempo y encontrar tu arrendatario ideal que califique a tus criterios.';
        }
        return view('users.owners.forms.third-step')->with(['user' => \Auth::user(), 'message1' => $message1, 'message2' => $message2, 'back' => $back]);
    }

    public function registrationThirdStepOne(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);
        $type = PropertyType::find($property->property_type_id);
        if($property->is_project == 1){
            $message1 = "Condiciones de Venta";
            $message2 = "Estas condiciones permiten determinar los potenciales clientes que califiquen a tus criterios de arriendo.";
        } else {
            $message1 = "Condiciones de Arriendo";
            $message2 = "Estas condiciones permiten determinar los potenciales arrendatarios que califiquen a tus criterios de arriendo.";
        }
        if ($request->isMethod('get')) {
            if($type->name == "Casa" || $type->name == "Departamento"){
                $type_property = 0;
            }
            if($type->name == "Oficina" || $type->name == "Local Comercial"){
                $type_property = 1;
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
            return view('users.owners.forms.third-step-one')->with([
                'user' => \Auth::user(),
                'property' => $property,
                'property_type' => $property_type,
                'type_property' => $type_property,
                'message1' => $message1,
                'message2' => $message2]);
        }elseif($request->isMethod('post')){
            //dd($request);

            if($property->is_project == 1){
                if (isset($request->rent)) {
                    $property->rent = str_replace(".","",$request->rent);
                }else{
                    $property->rent = 0;
                }
                if (isset($request->rent_up)) {
                    $property->rent_up = str_replace(".","",$request->rent_up);
                }else{
                    $property->rent_up = 0;
                }
                $property->save();
                if ($request->ajax()) {
                    return response(['success' => true]);
                } else {
                    //$user->updateProfileRedirect('properties.second-step.two','Propietario');
                    return redirect('/users/owner/registration/third-step/two');
                }
            } elseif($property->is_project == 0){
                if( $property->type_stay == 'LONG_STAY' ){

                    if (isset($request->rent)) {
                        $property->rent = str_replace(".","",$request->rent);
                    }else{
                        $property->rent = 0;
                    }
                    if (isset($request->rent_year_1)) {
                        $property->rent_year_1 = str_replace(".","",$request->rent_year_1);
                    }else{
                        $property->rent_year_1 = 0;
                    }
    
                    if (isset($request->rent_year_2)) {
                        $property->rent_year_2 = str_replace(".","",$request->rent_year_2);
                    }else{
                        $property->rent_year_2 = 0;
                    }
    
                    if (isset($request->rent_year_3)) {
                        $property->rent_year_3 = str_replace(".","",$request->rent_year_3);
                    }else{
                        $property->rent_year_3 = 0;
                    }
    
                    if (isset($request->common_expenses_limit)) {
                    $property->common_expenses_limit = str_replace(".","",$request->common_expenses_limit);
                    }else{
                        $property->common_expenses_limit = 0;
                    }
                    $property->term_year = $request->term_year;
                    if (isset($request->penalty_fees)) {
                        $property->penalty_fees = str_replace(".","",$request->penalty_fees);
                    }else{
                        $property->penalty_fees = 0;
                    }
                    $property->warranty_ticket = $request->warranty_ticket;
                    if (isset($request->warranty_ticket_price)) {
                        $property->warranty_ticket_price = str_replace(".","",$request->warranty_ticket_price);
                    }else{
                        $property->warranty_ticket_price = 0;
                    }
                    $property->tenanting_insurance = $property->propertyType()->first()->name != 'Bodega' ? $request->tenanting_insurance : 0;
                    $property->warranty_months_quantity = $request->warranty_months_quantity;
                    $property->months_advance_quantity = $request->months_advance_quantity;
    
                    $available_date = str_replace('/', '-', $request->available_date);
                    $property->available_date = date('Y-m-d', strtotime($available_date));
    
                    $property->tenanting_months_quantity = $request->tenanting_months_quantity;
                    $property->collateral_require = $request->collateral_require;
                    $property->furnished = $request->furnished;
                    $property->furnished_description = $request->furnished_description;
                    $user->updateProfileRedirect('users.owners.third-step.two','Propietario');
                    $property->save();
                    if ($request->ajax()) {
                        return response(['success' => true]);
                    }else {
                        
                        //$user->updateProfileRedirect('properties.second-step.two','Propietario');
                        return redirect('/users/owner/registration/third-step/two');
                    }
    
                    if (isset($request->rent)) {
                    $property->rent = str_replace(".","",$request->rent);
                    }else{
                    $property->rent = 0;
                    }
                    if (isset($request->common_expenses_limit)) {
                    $property->common_expenses_limit = str_replace(".","",$request->common_expenses_limit);
                    }else{
                        $property->common_expenses_limit = 0;
                    }
                    $property->tenanting_insurance = $request->tenanting_insurance;
                    $property->warranty_months_quantity = $request->warranty_months_quantity;
                    $property->months_advance_quantity = $request->months_advance_quantity;
    
                    $available_date = str_replace('/', '-', $request->available_date);
                    $property->available_date = date('Y-m-d', strtotime($available_date));
    
                    $property->tenanting_months_quantity = $request->tenanting_months_quantity;
                    $property->collateral_require = $request->collateral_require;
                    $property->furnished = $request->furnished;
                    $property->furnished_description = $request->furnished_description;
                    $user->updateProfileRedirect('users.owners.third-step.two','Propietario');
                    $property->save();
                    if ($request->ajax()) {
                        return response(['success' => true]);
                    }else {
                        
                        //$user->updateProfileRedirect('properties.second-step.two','Propietario');
                        return redirect('/users/owner/registration/third-step/two');
                    }
                } else {
                    $fechaql = str_replace('/', '-', $request->available_date);
                    $w = new Carbon($fechaql);
                    
                    if (isset($request->rent)) {
                        $property->rent = str_replace(".","",$request->rent);
                    }else{
                        $property->rent = 0;
                    }
                    if (isset($request->cleaning_rate)) {
                        $property->cleaning_rate = str_replace(".","",$request->cleaning_rate);
                    }else{
                        $property->cleaning_rate = 0;
                    }
                    $available_date = str_replace('/', '-', $request->available_date);
                    //$property->available_date = date('Y-m-d', strtotime($available_date));
                    $property->available_date = $w->toDateString();
                    $property->special_sale = intval($request->special_sale);
                    $property->week_sale = intval( intval(explode(' ',$request->week_sale)[0]) );
                    $property->month_sale = intval( intval(explode(' ',$request->month_sale)[0]) );
                    $property->minimum_nights = intval( $request->minimum_nights);
                    $property->checkin_hour = explode(' ',$request->checkin_hour)[1] == 'AM' ? intval($request->checkin_hour) : (intval($request->checkin_hour) + 12);
                    $property->checkout_hour = explode(' ',$request->checkout_hour)[1] == 'AM' ? intval($request->checkout_hour) : (intval($request->checkout_hour) + 12);
                    $property->save();
                    if ($request->ajax()) {
                        return response(['success' => true]);
                    }else {
                        return redirect('/users/owner/registration/third-step/two');
                    }
                
                }
            }
        }
    }
    public function registrationThirdStepTwo(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);
        if ($request->isMethod('get')) {
            
            return view('users.owners.forms.third-step-two')->with(['user' => \Auth::user(),'property' => $property]);
        }elseif($request->isMethod('post')){
            if($property->type_stay == 'LONG_STAY'){
                $property->visit = $request->visit;
                if ($request->visit) {
                /*$visit_from_date = str_replace('/', '-', $request->visit_from_date);
                $property->visit_from_date = date('Y-m-d', strtotime($visit_from_date));
                $visit_to_date = str_replace('/', '-', $request->visit_to_date);
                $property->visit_to_date = date('Y-m-d', strtotime($visit_to_date));
                $property->schedule_range = $request->schedule_range;*/
                $property->schedule_dates = $request->schedule_dates;
                }else{
                    $property->visit_from_date = null;
                    $property->visit_to_date = null;
                    $property->schedule_range = null;
                    $property->schedule_dates = null;
                }
                if( $property->propertyType()->first()->name == 'Bodega' && $property->is_project == 0){
                    $property->schedule_dates = $request->schedule_dates;
                    $user->updateProfileRedirect('users.owners.fifth-step','Propietario');
                    $property->propertiesFor()->sync(19);
                    $property->save();
                    return redirect('/users/owner/registration/fifth-step/two');
                }
                if($property_type->name == 'Estacionamiento' || $property_type->name == 'Terreno'){
                    $property->schedule_dates = $request->schedule_dates;
                    $user->updateProfileRedirect('users.owners.fifth-step','Propietario');
                    $property->propertiesFor()->sync(19);
                    $property->save();
                    return redirect('/users/owner/registration/fifth-step/two');
                } else {
                    $property->schedule_dates = $request->schedule_dates;
                    $user->updateProfileRedirect('users.owners.fourth-step','Propietario');
                    $property->save();
                    return redirect('/users/owner/registration/fourth-step');
                }

                $property->save();
                
                if ($request->ajax()) {
                    return response(['success' => true]);
                } else {
                    if($property_type->name != 'Estacionamiento'){
                        return redirect('/users/owner/registration/fourth-step');
                    } else {
                        return redirect('/users/owner/registration/fifth-step');
                    }
                    
                }
            } else {
                $property->schedule_dates = $request->schedule_dates;
                $property->save();
                $user->updateProfileRedirect('users.owners.fourth-step','Propietario');
                if ($request->ajax()) {
                    return response(['success' => true]);
                }else {
                    if( $property->propertyType()->first()->name == 'Bodega' && $property->is_project == 0){
                        $property->schedule_dates = $request->schedule_dates;
                        $user->updateProfileRedirect('users.owners.fifth-step','Propietario');
                        $property->propertiesFor()->sync(19);
                        $property->save();
                        return redirect('/users/owner/registration/fifth-step/two');
                    }
                    if($property_type->name == 'Estacionamiento' || $property_type->name == 'Terreno'){
                        $property->schedule_dates = $request->schedule_dates;
                        $user->updateProfileRedirect('users.owners.fifth-step','Propietario');
                        $property->propertiesFor()->sync(19);
                        $property->save();
                        return redirect('/users/owner/registration/fifth-step/two');
                    } else {
                        $property->schedule_dates = $request->schedule_dates;
                        $user->updateProfileRedirect('users.owners.fourth-step','Propietario');
                        $property->save();
                        return redirect('/users/owner/registration/fourth-step');
                    }
                }
            }
            
        }
    }

    public function registrationFourthStep(Request $request){
      return view('users.owners.forms.fourth-step')->with(['user' => \Auth::user()]);
    }

    public function registrationFourthStepOne(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);

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
            if($property->propertyType()->first()->name == 'Bodega'){
                // REDIRECCIONAR A PAGO DE MEMBRESIA
                $property->status = 0;
                $property->active = true;
                $property->save();
                $user->updateProfileRedirect('users.owners.memberships','Propietario');
                return redirect()->route('users.owners.memberships');
                    
            }
            return view('users.owners.forms.fourth-step-one')->with(['user' => \Auth::user(),'property' => $property,'properties_for' => PropertyFor::where('type', $type)->get(),'property_type' => $property_type, 'type' => $type]);
        }elseif($request->isMethod('post')){
            //if($property->type_stay == 'LONG_STAY'){
                if(isset($request->pet_preference)){
                    $property->pet_preference = $request->pet_preference;
                } else {
                    $property->pet_preference = 'no';
                }

                if(isset($request->smoking_allowed)){
                    $property->smoking_allowed = $request->smoking_allowed;
                } else {
                    $property->smoking_allowed = 0;
                }
                //$property->nationals_with_rut = $request->nationals_with_rut;
                //$property->foreigners_with_rut = $request->foreigners_with_rut;
                //$property->foreigners_with_passport = $request->foreigners_with_passport;
                $property->propertiesFor()->sync($request->property_for);
                $property->save();
                $user->updateProfileRedirect('users.owners.fifth-step','Propietario');
                if ($request->ajax()) {
                    return response(['success' => true]);
                }else {
                    return redirect('/users/owner/registration/fifth-step');
                }
            /*} else {
                $property->pet_preference = $request->pet_preference;
                $property->smoking_allowed = $request->smoking_allowed;
                $property->allow_small_child = $request->allow_small_child;
                $property->allow_baby = $request->allow_baby;
                $property->allow_parties = $request->allow_parties;
                $property->use_stairs = $request->use_stairs;
                $property->there_could_be_noise = $request->there_could_be_noise;
                $property->common_zones = $request->common_zones;
                $property->services_limited = $request->services_limited;
                $property->survellaince_camera = $request->survellaince_camera;
                $property->weaponry = $request->weaponry;
                $property->dangerous_animals = $request->dangerous_animals;
                $property->pets_friendly = $request->pets_friendly;
                //$property->nationals_with_rut = $request->nationals_with_rut;
                //$property->foreigners_with_rut = $request->foreigners_with_rut;
                //$property->foreigners_with_passport = $request->foreigners_with_passport;
                $property->propertiesFor()->sync($request->property_for);
                $property->save();
                $user->updateProfileRedirect('users.owners.fifth-step','Propietario');
                if ($request->ajax()) {
                    return response(['success' => true]);
                }else {
                    return redirect('/users/owner/registration/fifth-step');
                }
            }*/
           
        }
    }

    public function registrationFifthStep(Request $request){

      return view('users.owners.forms.fifth-step')->with(['user' => \Auth::user()]);
    }

    public function registrationFifthStepOne(Request $request){
        $user = \Auth::user();
        $banks = Bank::all();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);
        switch ($property_type->name) {
            case 'Casa':
                $type = 0;
                break;
            case 'Departamento':
                $type = 0;
                break;
            case 'Habitación':
                $type = 2;
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
            return view('users.owners.forms.fifth-step-one')->with(['user' => \Auth::user(), 'property' => $property, 'banks' => $banks,'property_type' => $type]);
        }elseif($request->isMethod('post')){
            $user->bank_id = $request->bank;
            $user->account_type = $request->account_type;
            $user->account_number = $request->account_number;
            $property->status = 0;
            $property->active = true;
            $property->save();
            $user->save();

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
                    }else {
                        $db_file = $property->files()->where('name', $fileName)->first();
                        $path = 'properties/'.$property->id.'/files/';
                        if ($db_file) {
                            \Storage::disk('local')->putFileAs('public/'.$path,$file,$fileName.'.pdf');
                            $db_file->original_name = $file->getClientOriginalName();
                            $db_file->path = 'storage/'.$path.$fileName.'.pdf';
                            $db_file->verified = 0;
                            $db_file->save();
                        }
                        }
                } catch (Exception $e) {
                }
            }
            $user->updateProfileRedirect('users.owners.fifth-step.two','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                if( $property->type_stay == 'SHORT_STAY' ){
                    Membership::attachMembership(5, \Auth::user());
                    return redirect()->route('profile.owner');
                }
                return redirect()->route('users.owners.fifth-step.two');
            }
        }
    }

    public function registrationFifthStepTwo(Request $request){
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
        $property_type = PropertyType::find($property->property_type_id);
        if($property->is_project == 1 || $property->type_stay == 'SHORT_STAY'){
            $property->active = true;
            $property->status = 0;
            $user->updateProfileRedirect('users.owners.memberships','Propietario');
            $property->save();
            return redirect()->route('profile.owner');
        }

        switch ($property_type->name) {
            case 'Estacionamiento':
                $back = 'users.owners.third-step.two';
                break;
            case 'Bodega':
                $back = 'users.owners.third-step.two';
                break;
            case 'Terreno':
                $back = 'users.owners.third-step.two';
                break;
            
            default:
                $back = 'users.owners.fifth-step.one';
                break;
        }
        
        if ($request->isMethod('get')) {
            return view('users.owners.forms.fifth-step-two')->with(['property' => $property, 'back' => $back]);
        } elseif($request->isMethod('post')){

            $db_file_front = $user->files()->where('name', 'id_front')->first();
            $db_file_back = $user->files()->where('name', 'id_back')->first();
            /*if($db_file_front->verified_ocr == 1 && $db_file_back->verified_ocr == 1){
                if($db_file_front->verified == 1 && $db_file_back->verified == 1){
                    $property->active = true;
                    $property->status = 0;
                } else {
                    $property->active = false;
                    $property->status = 1;
                }
            } else {
                $property->active = false;
                $property->status = 1;
            }*/
            $property->active = true;
            $property->status = 0;
            $user->updateProfileRedirect('users.owners.memberships','Propietario');
            $property->manage = $request->manage;
            $property->save();

            if ($request->ajax()) {
                return response(['success' => true]);
            } else {
                return redirect()->route('profile.owner');
            }

        }
    }


    public function registrationMembershipsForm(Request $request){
        $memberships = Membership::getOwnerMemberships();
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
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
        return view('users.owners.forms.memberships')->with([
            'user' => $user,
            'memberships' => $memberships,
            'property'=>$property,
            'back' => 'users.owners.fifth-step.one',
            'route' => 'users.owners.memberships-checkout',
            'payment' => $payment,
            'close' => 'start'
            ]);
    }

    public function registrationMembershipsFormUpdate(Request $request){
        $memberships = Membership::getOwnerMemberships();
        $user = \Auth::user();
        $property = $user->getOwnedProperties(true)->first();
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
        return view('users.owners.forms.memberships')->with([
            'user' => $user,
            'memberships' => $memberships,
            'property' => $property,
            'back' => 'profile.owner',
            'route' => 'users.owners.memberships-checkout.update',
            'payment' => $payment,
            'close' => 'profile.owner'
            ]);
    }

    public function registrationMembershipsFormBack(Request $request){
        $memberships = Membership::getOwnerMemberships();
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
        
        $property = $user->getOwnedProperties(true)->first();
        return view('users.owners.forms.memberships-back')->with(['user' => $user,
                                                        'memberships' => $memberships,
                                                        'property'=>$property,
                                                        'payment'=>$payment]);
    }

    public function registrationMembershipCheckout(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $membership = Membership::find($request->membership);
            return view('users.owners.forms.membership-checkout')->with([
                'user' => $user,
                'membership' => $membership,
                'property_id' => $request->property_id,
                'route' => 'users.owners.memberships',
                'back' => '/users/owner/memberships',
                'close' => 'start'
            ]);
        }elseif($request->isMethod('post')){
            // $membership = Membership::find($request->membership);
            // foreach ($user->memberships as $m) {
            //     if ($m->role_id == $membership->role_id) {
            //         $user->memberships()->detach($m->id);
            //     }
            // } #TODO: FIX THIS ROUTINE
            // $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
            // $user->notify(new OwnerProfile());
            // $property = $user->getOwnedProperties(true)->first();
            // $property->active = true;
            // $property->save();
            // $user->updateProfileRedirect(null,'owner');
            return response(['success' => true]);

        }
    }

    public function registrationMembershipCheckoutUpdate(Request $request){
        $user = \Auth::user();
        if ($request->isMethod('get')) {
            $membership = Membership::find($request->membership);
            return view('users.owners.forms.membership-checkout')->with([
                'user' => $user,
                'membership' => $membership,
                'property_id' => $request->property_id,
                'route' => 'users.owners.memberships.update',
                'back' => '/users/owner/memberships-u',
                'close' => 'profile.owner'
            ]);
        }elseif($request->isMethod('post')){
            // $membership = Membership::find($request->membership);
            // foreach ($user->memberships as $m) {
            //     if ($m->role_id == $membership->role_id) {
            //         $user->memberships()->detach($m->id);
            //     }
            // } #TODO: FIX THIS ROUTINE
            // $user->memberships()->attach($request->membership, array('expires_at' =>  Carbon::now()->addDays(30), 'purchased_at' => Carbon::now()));
            // $user->notify(new OwnerProfile());
            // $property = $user->getOwnedProperties(true)->first();
            // $property->active = true;
            // $property->save();
            // $user->updateProfileRedirect(null,'owner');
            return response(['success' => true]);

        }
    }

}
