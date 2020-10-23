<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{User, Country , City, Commune, Favourite, PropertyType, Property, Amenity, Membership, PropertyFor, File, Photo, Space, Bank, UserProperty};
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use App\Notifications\ApplyProperty as ApplyPropertyNotification;
use App\Notifications\ApplyPropertyOwner as ApplyPropertyOwnerNotification;
use App\Http\Requests\Property\ToggleFavourite as ToggleFavouriteRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
class PropertyController extends Controller
{
    public function showPropertyDetail($id){
        $property = Property::findOrFail($id);
        return view('pages.explore_details', ['p' => $property]); 
    }
    public function registrationFirstStep(Request $request){
        $user = \Auth::user();
        if(isset($request->id)){
            $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
            $property = Property::FindOrFail($id[0]->property_id);
        } else {
			$property = null;
        }
        if($request->isMethod('get')) {
            return view('properties.forms.first-step')->with(['user' => \Auth::user(), 'property' => $property]);
        } elseif($request->isMethod('post')){
            if (is_null($property)) {
                $property = new Property();
            }
            $property->is_project = $request->is_project;
            $property->property_type_id = 1;
            $property->active = false;
            $property->status = 4;
            $property->type_stay = 'LONG_STAY';
            
            if($request->is_project == 1){
                $property->redirect = '/properties/registration/first-step/one/';
            } elseif($request->is_project == 0){
                $property->redirect = '/properties/registration/select/';
            }

            $property->save();
            if ($property->users->count() == 0)  {
                $user->properties()->attach($property->id, ['type' => Property::TYPE_OWNER]);
            }
            
            if($property->is_project == 1){
                return redirect('/properties/registration/first-step/one/'.$property->id);
            } elseif($property->is_project == 0){
                return redirect('/properties/registration/select/'.$property->id);
            }

        }
    }
    public function registrationSelectStep(Request $request){
        $user = \Auth::user();
        if(isset($request->id)){
            $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
            $property = Property::FindOrFail($id[0]->property_id);
        } else {
			$property = null;
        }
        if($request->isMethod('get')){
            return view('properties.forms.select-step')->with(['user' => \Auth::user(), 'property' => $property]);
        } elseif($request->isMethod('post')){
            if( $request->estadia == 'short' ){
                $property->type_stay = 'SHORT_STAY';
            } else {
                $property->type_stay = 'LONG_STAY';
            }
            $property->redirect = '/properties/registration/first-step/one/';
            $property->save();
            return redirect('/properties/registration/first-step/one/'.$property->id);
        }
    }
	
    public function registrationFirstStepOne(Request $request){
        $user = \Auth::user();
        if(isset($request->id)){
            $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
            $property = Property::FindOrFail($id[0]->property_id);
            $type = PropertyType::FindOrFail($property->property_type_id);
			if($type->name == "Oficina" || $type->name == "Local Comercial"){
				$type_property = 1;
				
			}
			if($type->name == "Casa" || $type->name == "Departamento" || $type->name == "Habitación" || $type->name == "Estacionamiento" || $type->name == "Bodega"){
				$type_property = 0;
            }
		} else {
			$property = null;
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
            return view('properties.forms.first-step-one')->with([
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
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/properties/registration/first-step/one-one/'.$property->id);
            }
        }
    }
    public function registrationFirstStepOneOne(Request $request){
        $user = \Auth::user();
        if(isset($request->id)){
            $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
            $property = Property::FindOrFail($id[0]->property_id);
            $type = PropertyType::FindOrFail($property->property_type_id);
		} else {
			$property = null;
        }
        if($property->is_project == 1){
            $message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los compradores encontrar tu propiedad.';
        } else {
            $message1 = 'Esta información es importante, para conocer a detalle toda la información de la propiedad y sea más fácil para los arrendatarios encontrar tu propiedad.';
        }
        if ($request->isMethod('get')) {

            if($type->name == "Oficina" || $type->name == "Local Comercial" || $type->name == "Bodega"){
                $type_property = 1;
                
            }
            if($type->name == "Casa" || $type->name == "Departamento" || $type->name == "Habitación" || $type->name == "Estacionamiento" || $type->name == "Terreno" || $type->name == "Bodega"){
                $type_property = 0;
            }

            return view('properties.forms.first-step-one')->with([
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
            $property->redirect = '/properties/registration/first-step/two/';
			$property->save();
            //$user->updateProfileRedirect('properties.first-step.two','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                return redirect('/properties/registration/first-step/two/'.$property->id);
            }
        }
    }

    public function registrationFirstStepTwo(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::FindOrFail($id[0]->property_id);
        if ($request->isMethod('get')) {
            return view('properties.forms.first-step-two')->with(['user' => \Auth::user(),'property' => $property,'cities' => Country::find(39)->cities]);

        }elseif($request->isMethod('post')){

            $property->city_id = $request->city;
            $property->address = $request->address;
            $property->address_details = $request->address_details;
            $property->latitude = $request->latitude;
            $property->longitude = $request->longitude;
            $property->redirect = '/properties/registration/first-step/three/';
            $property->save();

            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                //$user->updateProfileRedirect('properties.first-step.three','Propietario');
                return redirect('/properties/registration/first-step/three/'.$property->id);
            }
        }
    }
    public function registrationFirstStepThree(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        #dd( $property->photos);
        if ($request->isMethod('get')) {
          return view('properties.forms.first-step-three')->with(['user' => \Auth::user(),'property' => $property,
          'spaces' => Space::all()]);
        }elseif($request->isMethod('post')){
            $property->redirect = '/properties/registration/first-step/four/'.$property->id;
            $property->save();
            //$user->updateProfileRedirect('properties.first-step.four','Propietario');
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                if( $property->propertyType()->first()->name == 'Bodega' ){
                    $property->redirect = '/properties/registration/second-step/'.$property->id;
                    $property->save();
                    return redirect('/properties/registration/second-step/'.$property->id);
                    
                }
                return redirect('/properties/registration/first-step/four/'.$property->id);
            }
        }
    }
    public function registrationFirstStepFour(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
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

            if($type_property == 3 || $type_property == 4 || $type_property == 5){
                $property->redirect = '/properties/registration/second-step/';
            } else {
                $property->redirect = '/properties/registration/first-step/five/';
            }
            
            $property->save();
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                //$user->updateProfileRedirect('properties.first-step.five','Propietario');
                if($type_property == 3 || $type_property == 4 || $type_property == 5){
                    return redirect('/properties/registration/second-step/'.$property->id);
                } else {
                    return redirect('/properties/registration/first-step/five/'.$property->id);
                }
            }
        }
    }
    public function registrationFirstStepFive(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
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
        
        if($type_property == 3){
			return redirect('/properties/registration/first-step/four/'.$property->id);
		}
        if ($request->isMethod('get')) {
            return view('properties.forms.first-step-five')->with(['user' => \Auth::user(),'property_amenities' => Amenity::where('type', false)->get(),'property' => $property,'common_amenities' => Amenity::where('type', true)->get(), 'basic_services' => Amenity::where('type', '2')->get(), 'rules_amenities' => Amenity::where('type', '3')->get(), 'details_amenities' => Amenity::where('type', '4')->get(), 'possessions' => Amenity::where('type', '5')->get(), 'type_property' => $type_property]);
        }elseif($request->isMethod('post')){
            $property->amenities()->sync($request->amenities);
            $property->redirect = '/properties/registration/second-step/';
            $property->save();
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                //$user->updateProfileRedirect('properties.second-step','Propietario');
                return redirect('/properties/registration/second-step/'.$property->id);
            }
        }
    }
    public function registrationSecondStep(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        $property->redirect = '/properties/registration/second-step/one/';
        $property->save();
        $property_type = PropertyType::find($property->property_type_id);
        switch ($property_type->name) {
            case 'Estacionamiento':
                $back = 'properties.first-step.four';
                break;
            case 'Bodega':
                $back = 'properties.first-step.four';
                break;
            case 'Terreno':
                $back = 'properties.first-step.four';
                break;
            
            default:
                $back = 'properties.first-step.five';
                break;
        }
        if($property->is_project == 1){
            $message1 = 'CONDICIONES DE VENTA';
            $message2 = 'Todos los datos que configures te ayudaran a destacar tu propiedad, aumentar las probabilidades de venta en menos tiempo y encontrar tu comprador ideal que califique a tus criterios.';
        } else {
            $message1 = 'CONDICIONES DE ARRIENDO';
            $message2 = 'Todos los datos que configures te ayudaran a destacar tu propiedad, aumentar las probabilidades de arriendo en menos tiempo y encontrar tu arrendatario ideal que califique a tus criterios.';
        }
        //$user->updateProfileRedirect('properties.second-step.one','Propietario');
        return view('properties.forms.second-step')->with(['user' => \Auth::user(), 'property' => $property, 'message1' => $message1, 'message2' => $message2, 'back' => $back]);
    }

    public function registrationSecondStepOne(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        $property_type = PropertyType::find($property->property_type_id);
        $type = PropertyType::find($property->property_type_id);
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
            return view('properties.forms.second-step-one')->with(['user' => \Auth::user(),'property' => $property, 'property_type' => $property_type, 'type_property' => $type_property]);
        }elseif($request->isMethod('post')){
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
                    return redirect('/properties/registration/second-step/two/'.$property->id);
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
                    $property->redirect = '/properties/registration/second-step/two/';
                    $property->save();
                    if ($request->ajax()) {
                        return response(['success' => true]);
                    }else {
                        
                        //$user->updateProfileRedirect('properties.second-step.two','Propietario');
                        return redirect('/properties/registration/second-step/two/'.$property->id);
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
                    $property->redirect = '/properties/registration/second-step/two/';
                    $property->save();
                    if ($request->ajax()) {
                        return response(['success' => true]);
                    }else {
                        
                        //$user->updateProfileRedirect('properties.second-step.two','Propietario');
                        return redirect('/properties/registration/second-step/two/'.$property->id);
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
                        return redirect('/properties/registration/second-step/two/'.$property->id);
                    }
                
                }
            }
            
        }
    }
    public function registrationSecondStepTwo(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        $property_type = PropertyType::find($property->property_type_id);

        if ($request->isMethod('get')) {
            return view('properties.forms.second-step-two')->with(['user' => \Auth::user(),'property' => $property]);
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
                    $property->redirect = '/properties/registration/fourth-step/two/';
                    $property->propertiesFor()->sync(19);
                    $property->save();
                    return redirect('/properties/registration/fourth-step/two/'.$property->id);
                }
                if($property_type->name == 'Estacionamiento' || $property_type->name == 'Terreno'){
                    $property->schedule_dates = $request->schedule_dates;
                    $property->redirect = '/properties/registration/fourth-step/two/';
                    $property->propertiesFor()->sync(19);
                    $property->save();
                    return redirect('/properties/registration/fourth-step/two/'.$property->id);
                } else {
                    $property->schedule_dates = $request->schedule_dates;
                    $property->redirect = '/properties/registration/third-step/';
                    $property->save();
                    return redirect('/properties/registration/third-step/'.$property->id);
                }
                if ($request->ajax()) {
                    return response(['success' => true]);
                }else {
                    //$user->updateProfileRedirect('properties.third-step','Propietario');
                }
            } else {
                
                if ($request->ajax()) {
                    $property->schedule_dates = $request->schedule_dates;
                    $property->redirect = '/properties/registration/third-step/';
                    $property->save();
                    return response(['success' => true]);
                }else {
                    if( $property->propertyType()->first()->name == 'Bodega' && $property->is_project == 0){
                        $property->schedule_dates = $request->schedule_dates;
                        $property->redirect = '/properties/registration/fourth-step/two/';
                        $property->propertiesFor()->sync(19);
                        $property->save();
                        return redirect('/properties/registration/fourth-step/two/'.$property->id);
                    }
                    if($property_type->name == 'Estacionamiento' || $property_type->name == 'Terreno'){
                        $property->schedule_dates = $request->schedule_dates;
                        $property->redirect = '/properties/registration/fourth-step/two/';
                        $property->propertiesFor()->sync(19);
                        $property->save();
                        return redirect('/properties/registration/fourth-step/two/'.$property->id);
                    } else {
                        $property->schedule_dates = $request->schedule_dates;
                        $property->redirect = '/properties/registration/third-step/';
                        $property->save();
                        return redirect('/properties/registration/third-step/'.$property->id);
                    }
                }
            
            }
        }
    }

    public function registrationThirdStep(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        $property->redirect = '/properties/registration/third-step/one/';
        $property->save();
        //$user->updateProfileRedirect('properties.third-step.one','Propietario');
        return view('properties.forms.third-step')->with(['user' => \Auth::user(), 'property' => $property]);
    }

    public function registrationThirdStepOne(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
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
            return view('properties.forms.third-step-one')->with(['user' => \Auth::user(),'property' => $property,'properties_for' => PropertyFor::where('type', $type)->get(),'property_type' => $property_type, 'type' => $type]);
        }elseif($request->isMethod('post')){
            if($type == 0){
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
            }
            //$property->nationals_with_rut = $request->nationals_with_rut;
            //$property->foreigners_with_rut = $request->foreigners_with_rut;
            //$property->foreigners_with_passport = $request->foreigners_with_passport;
            $property->propertiesFor()->sync($request->property_for);
            $property->redirect = '/properties/registration/fourth-step/';
            $property->save();
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                //$user->updateProfileRedirect('properties.fourth-step','Propietario');
                return redirect('/properties/registration/fourth-step/'.$property->id);
            }

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
            $property->redirect = '/properties/registration/fourth-step/';
            $property->save();
            if ($request->ajax()) {
                return response(['success' => true]);
            }else {
                //$user->updateProfileRedirect('properties.fourth-step','Propietario');
                return redirect('/properties/registration/fourth-step/'.$property->id);
            }
        }
    }

    public function registrationFourthStep(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        $property->redirect = '/properties/registration/fourth-step/one/';
        $property->save();
        //$user->updateProfileRedirect('properties.fourth-step.one','Propietario');
        return view('properties.forms.fourth-step')->with(['user' => \Auth::user(), 'property' => $property]);
    }

    public function registrationFourthStepOne(Request $request){
        $user = \Auth::user();
        $banks = Bank::all();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
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
            return view('properties.forms.fourth-step-one')->with(['property' => $property,'property_type' => $type]);
        }elseif($request->isMethod('post')){
            
            $property->redirect = '/properties/registration/fourth-step/two/';
            $property->save();

            $name = $user->firstname.' '.$user->lastname;
            $name = strtoupper($name);

            $files = $request->files->all();

            //return $files['property_certificate'];

            foreach ($files as $fileName => $file) {
                try {
                    $db_file = $property->files()->where('name', $fileName)->first();
                    $path = 'properties/'.$property->id.'/files/';
                    if ($db_file) {
                        \Storage::disk('local')->putFileAs('public/'.$path,$file,$fileName.'.pdf');
                        $db_file->original_name = $file->getClientOriginalName();
                        $db_file->path = 'storage/'.$path.$fileName.'.pdf';
                        $db_file->verified = 0;
                        $db_file->save();
                    }
                } catch (\Exception $e) {
                }
            }
            if ($request->ajax()) {
                return response(['success' => true, 'file' => $db_file]);
            } else {
                //$user->updateProfileRedirect(null,'Propietario');
                return redirect('/properties/registration/fourth-step/two/'.$property->id);
            }
        }
    }

    public function registrationFourthStepTwo(Request $request){
        $user = \Auth::user();
        $id = DB::table('users_has_properties')->where('user_id',$user->id)->where('property_id', $request->id)->where('type',1)->orderBy('id','DESC')->take(1)->get();
        $property = Property::find($id[0]->property_id);
        $property_type = PropertyType::find($property->property_type_id);

        if($property->is_project == 1 || $property->type_stay == 'SHORT_STAY'){
            $property->active = true;
            $property->status = 0;
            $property->redirect = null;
            $property->save();
            return redirect()->route('profile.owner');
        }

        switch ($property_type->name) {
            case 'Estacionamiento':
                $back = 'properties.second-step.two';
                break;
            case 'Bodega':
                $back = 'properties.second-step.two';
                break;
            case 'Terreno':
                $back = 'properties.second-step.two';
                break;
            
            default:
                $back = 'properties.fourth-step.one';
                break;
        }
        
        if ($request->isMethod('get')) {
            return view('properties.forms.fourth-step-two')->with(['property' => $property, 'back' => $back]);
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
            $property->redirect = null;
            $property->manage = $request->manage;
            $property->save();

            if ($request->ajax()) {
                return response(['success' => true]);
            } else {
                return redirect()->route('profile.owner');
            }

        }
        
        
    }

	public function getPhotos(Request $request){

		return response(['cover' => Photo::with('space')->where(['property_id' => $request->property_id, 'cover' => true])->get(),
						'photos' => Photo::with('space')->where(['property_id' => $request->property_id, 'cover' => false])->get()]);
	}

	public function savePhoto(Request $request){

		$property = Property::find($request->property_id);        

        $files = $request->files->all();

        $db_file = Photo::find($request->photo_id);      

        if ($db_file) {
            $db_file->delete();
        }
        $db_file = new Photo;
		// return response($files);
		foreach ($files as $file) {            
			// code...
			// $file = $files[$request->photo_name];            
			try {
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
				\Storage::disk('local')->putFileAs('public/properties/'.$property->id.'/photos/',$file,$db_file->name);
				$db_file->property_id = $property->id;
				$db_file->original_name = $file->getClientOriginalName();
				$db_file->path = '/storage/properties/'.$property->id.'/photos/'.$db_file->name;

				$db_file->save();
			} catch (Exception $e) {
			}
		}
		return response(["path" => $db_file->path, "photo_id" => $db_file->id, "space_id" => $db_file->space_id]);
	}
	public function changeSpace(Request $request){
		$photo = Photo::where([ 'id' => $request->photo_id ])->first();
		if ( !is_null($photo) ) {
			$photo->space_id = $request->space_id;
			$photo->save();
			return response(["path" => $photo->path, "photo_id" => $photo->id]);
		}
	}
	public function deletePhoto(Request $request){

        if(isset($request->photo_id)){
            $photo = Photo::find($request->photo_id);
        }
        if(isset($request->filename)){
            $photo = Photo::where('original_name', $request->filename)->first();
        }
		
		if ( !is_null($photo) ) {
			$photo->delete();
			return response( [ "operation" => true ] );
		}
		return response( [ "operation" => false ] );
	}
	public function index(Request $request) {
        $property = Property::with(['photos.space'])->where('id', $request->property_id)->first();
        
        $address = $property->address;
        $pt = explode(",", $address);
        $city = '';
        if (count($pt) > 1) $city = array_pop($pt);
        $property['address'] = implode(',', $pt);
        $property['city'] = $city;
  
        $property['amenities'] = $this->getOnlyId($property->amenities()->get());
        $property['properties_for'] = $this->getOnlyId($property->propertiesFor()->get());
  
  
        $imagen = DB::table('photos')->where("property_id", $property->id)->first();
        $property['image'] = $imagen->path;

        $property['files'] = DB::table('files')->where('property_id', $property->id)->get();

        $property['demand_property'] = (int)DB::select('select sf_demand_property(?) as demand', [$property->id])[0]->demand;

        return $property;
    }
    
    private function getOnlyId($w) {
        $ret = [];
        foreach ($w as $v)
            $ret[] = $v->id;

        return $ret;
    }

	public function owner(Request $request) {
        $user_property = UserProperty::all();

        if(Property::findOrFail($request->property_id)->getOwner()){
            $owner = Property::findOrFail($request->property_id)->getOwner();
            $owner['type'] = 'Owner';
        } elseif (Property::findOrFail($request->property_id)->getAgent()) {
            $owner = Property::findOrFail($request->property_id)->getAgent();
            $owner['type'] = 'Agent';
        }

        $db_file_front = File::where('user_id',$owner->id)->where('name', 'id_front')->first();
        $db_file_back = File::where('user_id',$owner->id)->where('name', 'id_back')->first();
        if(true){
            if($db_file_front->verified == 1 /*&& $db_file_back->verified == 1*/){
                $verify = true;
            } else {
                $verify = false;
            }
        } else {
            $verify = false;
        }
        $owner['verify'] = $verify;
        $user = \Auth::user();
        if($user){
            $membership = $user->getTenantMerbershipOnce();
            if($membership){
                $membership_name = $membership->name;
                switch ($membership_name) {
                    case 'Basic':
                        $owner['email'] = null;
                        $owner['phone'] = null;
                        $owner['phone_code'] = null;
                        break;
                    case 'Select':
                        $owner['email'] = null;
                        break;
                    default:
                        $owner['email'] = null;
                        $owner['phone'] = null;
                        $owner['phone_code'] = null;
                        break;
                }
            } else {
                $owner['email'] = null;
                $owner['phone'] = null;
                $owner['phone_code'] = null;
            }
            $owner->account_number = null;
        } else {
            $owner['email'] = null;
            $owner['phone'] = null;
            $owner['phone_code'] = null;
        }
        
        if(!$owner) return response('{}');
            if($owner['type'] == 'Owner'){
                $owner['membership_name'] = $owner->getOwnerMerbershipOnce()->name;
            } elseif($owner['type'] == 'Agent') {
                $owner['membership_name'] = $owner->getAgentMerbershipOnce()->name;
            }
            
            
		return response($owner);
    }
    
    public function tenant(Request $request) {
        $user = \Auth::user();
        //$tenant = \DB::table('users')->select('firstname','lastname')->where('id', $user->id)->first();
        if($user){
            $membership = $user->getTenantMerbershipOnce();
            if($membership){
                $user->membership_name = $membership->name;
                $user->account_number = null;
                $user->account_type = null;
                return response($user,200);
            } else {
                return response('{}');
            }
        }
    }

	public function userScore(Request $request) {
		return Membership::checkTenantMemberships() ? (\Auth::user())->getScoringAttribute($request->property_id) : 0;
	}

	public function amenities(Request $request) {
		return response(Property::find($request->property_id)->amenities()->get());
	}

	public function propertiesFor(Request $request) {
		return response(Property::find($request->property_id)->propertiesFor()->get());
    }
    
    public function propertiesType(Request $request){
        return response(Property::find($request->property_id)->propertyType()->first());
    }

    public function getPropertyById(Request $request, $id)
    {
        return response(Property::find($request->property_id)->propertiesFor()->get());
    }

	/**
	 * This methods save or delete a Favourite property of a
	 * user.
	 * Switch between Favourite or not.
	 */
	public function storeToggleAsFavourite(ToggleFavouriteRequest $request) {

		$user = \Auth::user();

		// Verifys it's exits
		$favouriteBuilder = Favourite::where('property_id', '=', $request->property_id)->where('user_id', '=', $user->id)->get();
		$exists = ($favouriteBuilder->count() > 0);

		if($exists) {
			// Delete as Favourite
			if($favouriteBuilder->first()->delete())
				$exists = false;
		}
		else {
			$favourite = new Favourite();
			$favourite->user_id = $user->id;
			$favourite->property_id = $request->property_id;

			if($favourite->save())
				$exists = true;
		}

		return (int) $exists;
	}

	public function storeApplication(\App\Http\Requests\Property\StoreApplication $request) {

		$property = Property::findOrFail($request->property_id);
        $user = \Auth::user();

        $owner_property = DB::table('users')->select('users.email','users.id','users.firstname','users.lastname')
            ->join('users_has_properties', 'users.id', '=', 'users_has_properties.user_id')
            ->where('users_has_properties.property_id', $property->id)->first();

        // Verifiy if the user has apply before
        
		$has_postulate = \App\ApplyProperty::where('property_id', $property->id)
			->where('user_id', $user->id)
			->first();
        
		if(!$has_postulate || $property->type_stay == 'SHORT_STAY') {
            /*
            if( $user->files()->where('name','id_front')->get()->first()->verified   ){
                $espera = 0;
            } else {
                $espera = 1;
            }
            */
            $espera = 0;
			$application = new \App\ApplyProperty([
				'property_id' => $property->id,
                'user_id'	  => $user->id,
                'espera'      => $espera
            ]);

            
            //dd($application);
            
            
            //$application = \App\ApplyProperty::create(['property_id' => $property->id, 'user_id' => $user->id]);
            /*
            DB::insert('insert into users_has_properties (type, property_id, user_id) values (?,?,?)', ['type' => 2,
                                                                                                        'property_id' => $property->id,
                                                                                                        'user_id' => $user->id]);
            $id_postulacion = DB::select('select id from users_has_properties where type = ? and property_id = ? and user_id = ?', [2,
                                                                                                        $property->id,
                                                                                                        $user->id]);                                                           
            DB::insert('insert into postulates (id, state, created_at, updated_at) values (?,?,?,?)', ['id' => $id_postulacion,
                                                                                                        'state' => 0,
                                                                                                        'created_at' => '2019-05-31 17:25:48',
                                                                                                        'updated_at' => '2019-05-31 17:25:48']);
            */
            //if(true){
            //if($application) {
            //if( true ) {
			if($application->save()) {
                if( $request->days && $property->type_stay == 'SHORT_STAY'){
                    DB::table('users_has_postulates_days')->insert(
                        ['id' => $application->id, 'days' => $request->days]
                    );

                    $postulation = \App\ApplyProperty::find($application->id);
                    $postulation->state = 6;
                    $postulation->save();
                    //DB::insert('insert into `users_has_postulates_days` (id, days) values (?,?)', [, ] );
                }
                // Send notifications
                if(!$espera){
                    \Notification::route('mail', $user->email)
                    //->notify(new ApplyPropertyNotification($application));
                    ->notify(new ApplyPropertyNotification($property->id,$property->name,$user->firstname,$user->lastname));
                    
                    \Notification::route('mail', $owner_property->email)
                    ->notify(new ApplyPropertyOwnerNotification($property->id,$property->name,$owner_property->firstname,$owner_property->lastname));
                
                    //return 'El property_id es: '.$property->id.' y el user_id es: '.$user->id;
                } 

                return response()->json(['espera' => $espera]);
    
			}

			throw new \Exception('Ha ocurrido un error inesperado. No se pudo guardar la postulacion.');
		}
    }
    public function verifiedCBRS(Request $request){

        $user = \Auth::user();

        //$url = url($file->path);

        $name = strtoupper($user->firstname);
        $fullname = strtoupper($user->firstname . ' ' . $user->lastname);

        $document = str_replace('.','',$user->document_number);

        $document_rut = explode("-",$document);

        $rut_d = $document_rut[0];
        $rut_dv = $document_rut[1];

        $files = File::where('property_id', $request->id)->get();
        $property = Property::find($request->id);

        $property_certificate = null;
        $last_electricity_bill = null;
        $last_water_bill = null;
        $water_message = null;
        $elect_message = null;
        $cert_message = null;
        $e_alert = null;
        $c_alert = null;
        $w_alert = null;
        $client = new Client();

        foreach ($files as $file) {

            $db_file = File::find($file->id);
            switch($file->name) {
                case 'property_certificate': 
                    if($file->verified == 0){
                        if($file->path != null){
                            
                            $url = url($file->path);
                            $client = new Client();
                            
                            // 1) Busca el nombre del usuario dentro del CBRS | variables de respuesta son JSON *BUSQUEDA (OK : NOK)
                            //return env('VIN_GET_NAME_CBRS').'&url='.$url.'&nombre='.$name;
                            $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$url}&bucket=pdf&extension=pdf");

                            /*$res_name_get = $client->get(env('VIN_GET_NAME_CBRS').'&url='.$url.'&nombre='.$name);
                            $res_name = json_decode($res_name_get->getBody());*/
        
                            /* Estan comentados sin uso para no consumirlos hasta no definir que hacer con ellos
                            //Busca la comuna del CBRS | variables de respuesta son JSON *comuna *rolavaluo
                            $res_comuna = $client->get(env('VIN_GET_COMUNA_CBRS').'&url='.$url);
                            $res_comuna =json_decode($res_comuna->getBody());
                            //Busca busca la informacion de folio,numero,año del CBRS | variables de respuesta son JSON *OK que es una booleano *fojas *numero *ano
                            $res_info = $client->get(env('VIN_GET_INFO_CBSR').'&url='.$url);
                            $res_info = json_decode($res_info->getBody());*/

                            if($res_s3->getBody()){
                                $json_s3 = json_decode($res_s3->getBody());
                                if(isset($json_s3->archivo)){
                                    $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                                    if($res_read->getBody()){
                                        $stream = $res_read->getBody();
                                        $contents = $stream->getContents(); 
                                        if(!empty($contents)){
                                            if(stripos($contents, $fullname)){
                                                // 2) Busca el rut del propietario del CBRS | variables de respuesta *RUT *DV
                                                $res_rut_get = $client->get('https://cbrsfaas.azurewebsites.net/api/cbrs-getcbrsrut?code=QLTMt/ead7jXva5n2HQvEb5TfbpcB0qTrZoe6nMsTPCWsSpN9LApTA==&url='.$url);
                                                if($res_rut_get->getBody()){
                                                    $res_rut = json_decode($res_rut_get->getBody());
                                                    if($res_rut != null){
                                                        if($res_rut->RUT == $rut_d && $res_rut->DV == $rut_dv){
                                                            // 3)Busca el Nombre de la persona perteneciente del rut | variables de respuesta *nombre
                                                            $res_info_get = $client->get('https://cbrsfaas.azurewebsites.net/api/CBRS-getCBRSusuarioxrut?code=Zndws/kMVP7/YgLJ1OfOdoF9VfOrG5EfxcDA45PDMDFySISnCvsqVA==&rut='.$rut_d.'&dv='.$rut_dv);
                                                            $res_info = json_decode($res_info_get->getBody());
                                                            if(isset($res_info->nombre)){
                                                                if($res_info->nombre == $fullname){
                                                                    // 4) Busca el codigo de verificacion del CBRS | variables de respuesta son JSON *codigoverificacion
                                                                    $res_code_get = $client->get('https://cbrsfaas.azurewebsites.net/api/CBRS-getCBRScodver?code=qRIttG560e0HeyLZ1HaGvrSNaclZaWbNIrW5uREFw4Y4eW2r9k7dRQ==&url='.$url);
                                                                    $res_code = json_decode($res_code_get->getBody());
                                                                    if($res_code->codigoverificacion){
                                                                        $db_file->verified = 1;
                                                                        $cert_message = 'Se a valido el Certificado de Propiedad exitosamente';
                                                                        //$property->verified = 1;
                                                                        //$property->save();
                                                                        $db_file->code = $res_code->codigoverificacion;
                                                                        $c_alert = 200;
                                                                    } else {
                                                                        $cert_message = 'No se ha encontrado codigo de verificación del Certificado de Propiedad';
                                                                        $db_file->verified = 3;
                                                                        $c_alert = 401;
                                                                    }
                                                                } else {
                                                                    $cert_message = 'El nombre del propietario no concuerda con nuestros registros';
                                                                    $db_file->verified = 3;
                                                                    $c_alert = 401;
                                                                }
                                                            } else {
                                                                $cert_message = 'No se encontro Nombre con el Rut Registrado';
                                                                $db_file->verified = 3;
                                                                $c_alert = 401;
                                                            }
                                                            
                                                        } else {
                                                            $cert_message = 'El rut no concuerda con nuestros registros';
                                                            $db_file->verified = 3;
                                                            $c_alert = 401;
                                                        }
                                                    } else {
                                                        $cert_message = 'No se encontro encontro rut';
                                                        $db_file->verified = 3;
                                                        $c_alert = 401;
                                                    }
                                                    
                                                } else {
                                                    $cert_message = 'No se encontro rut en el documento';
                                                    $db_file->verified = 3;
                                                    $c_alert = 401;
                                                }
                                                
                                            } else {
                                                $cert_message = 'No se encuentra el propietario en el Certificado de Propiedad';
                                                $db_file->verified = 3;
                                                $c_alert = 401;
                                            }
                                            $property_certificate = $db_file->verified;
                                            $db_file->save();
                                                
                                        } else {
                                            $cert_message = 'El archivo no tiene contenido';
                                            $c_alert = 401;
                                        }
                                    } else {
                                        $cert_message = 'El archivo con tiene contenido';
                                        $c_alert = 401;
                                    }
                                } else {
                                    $cert_message = 'No se pudo cargar el archivo para validar';
                                    $c_alert = 401;
                                }
                            } else {
                                $cert_message = 'No se pudo cargar el archivo para validar';
                                $c_alert = 401;
                            }
                        } else {
                            $cert_message = 'No se encuentra archivo de Certificado de Propiedad por favor cargue uno para validarlo';
                            $c_alert = 401;
                        }
                    } else {
                        if($file->verified == 1){
                            $cert_message = 'El Certificado de Propiedad ya ha sido validado anteriormente';
                            $c_alert = 200;
                        } else {
                            $cert_message = 'El Certificado de Propiedad ya ha sido validado pero no cumplio con la informacion requerida debe de modificar este archivo para realizar otra validación';
                            $c_alert = 401;
                        }
                    }
                    break;
                case 'last_electricity_bill':
                    if($file->verified == 0){
                        if($file->path != null){
                        
                            $url = url($file->path);

                            $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$url}&bucket=pdf&extension=pdf");
                            
                            /*
                            // 1) Busca el nombre del usuario dentro del ultimo recibo de luz | variables de respuesta son JSON *BUSQUEDA (OK : NOK)
                            $res_name_get = $client->get(env('VIN_GET_NAME_CBRS').'&url='.$url.'&nombre='.$name);
                            $res_name = json_decode($res_name_get->getBody());
            
                            // 2) Busca el rut del usuario dentro del ultimo recibo de luz | varibles de respuesta son JSON *BUSQUEDA (OK : NOK)
                            $res_rut_get = $client->get(env('VIN_GET_NAME_CBRS').'&url='.$url.'&nombre='.$rut_d);
                            $res_rut = json_decode($res_name_get->getBody());
            
                            // 3) Busca si existe el año dentro del  ultimo recibo de luz | varibles de respuesta son JSON *BUSQUEDA (OK : NOK)
                            $res_year_get = $client->get(env('VIN_GET_NAME_CBRS').'&url='.$url.'&nombre='.date('Y'));
                            $res_year = json_decode($res_name_get->getBody());*/

                            if($res_s3->getBody()){
                                $json_s3 = json_decode($res_s3->getBody());
                                if(isset($json_s3->archivo)){
                                    $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                                    if($res_read->getBody()){
                                        $stream = $res_read->getBody();
                                        $contents = $stream->getContents(); 
                                        if(!empty($contents)){
                                            if(stripos($contents, $fullname)){
                                                if(stripos($contents, $rut_d)){
                                                    if(stripos($contents, date('Y'))){
                                                        $elect_message = 'Se ha validado del recibo de electricidad exitosamente';
                                                        $db_file->verified = 1;
                                                        //$property->verified = 1;
                                                        //$property->save();
                                                        //$db_file->code = $res_code->codigo;
                                                        $e_alert = 200;
                                                    } else {
                                                        $elect_message = 'El recibo de electricidad no se encuentra actualizado';
                                                        $db_file->verified = 3;
                                                        $e_alert = 401;
                                                    }
                                                } else {
                                                    $elect_message = 'No se encuentra el rut del propietario en el recibo de electricidad';
                                                    $db_file->verified = 3;
                                                    $e_alert = 401;
                                                }
                                            } else {
                                                $elect_message = 'No se encuentra el propietario en el recibo de electricidad';
                                                $db_file->verified = 3;
                                                $e_alert = 401;
                                            }
                                        } else {
                                            $elect_message = 'No se encuentro contenido en el archivo';
                                            $db_file->verified = 3;
                                            $e_alert = 401;
                                        }
                                    } else {
                                        $elect_message = 'No se encuentro cuerpo en el archivo';
                                        $db_file->verified = 3;
                                        $e_alert = 401;
                                    }
                                } else {
                                    $elect_message = 'No se encuentro variable archivo';
                                    $db_file->verified = 3;
                                    $e_alert = 401;
                                }
                            } else {
                                $elect_message = 'No se encuentro cuerpo en el archivo';
                                $db_file->verified = 3;
                                $e_alert = 401;
                            }
                            $last_electricity_bill = $db_file->verified;
                            $db_file->save();
                        } else {
                            $elect_message = 'No se encuentra archivo de ultimo recibo de electricidad por favor cargue uno para validarlo';
                            $e_alert = 401;
                        }
                    } else {
                        if($file->verified == 1){
                            $elect_message = 'El ultimo recibo de electricidad ya ha sido verificado anteriormente';
                            $e_alert = 200;
                        } else {
                            $elect_message = 'El ultimo recibo de electricidad ya ha sido verificado anteriormente pero no cumplio con los requisitos por favor modifica el archivo para poder realizar otra validación';
                            $e_alert = 401;
                        }
                    }
                    break;
                case 'last_water_bill': 
                    if($file->verified == 0){
                        if($file->path != null){
                        
                            $url = url($file->path);

                            $res_s3 = $client->get("https://vindexerfaas.azurewebsites.net/api/VIDEOINDEXER-Url2Bkt?code=1xf2a2y8aIsgylkjQkllDgyrqpChOkUSeIPyu23M5MACC8Bau8qz/A==&url={$url}&bucket=pdf&extension=pdf");

                            if($res_s3->getBody()){
                                $json_s3 = json_decode($res_s3->getBody());
                                if(isset($json_s3->archivo)){
                                    $res_read = $client->get("https://ocroptfaas.azurewebsites.net/api/ocr-getbuscaenpdf?code=Ja6qwGaOlGcVaorTktErkHemg5827CQwQsqjW22V6mUTvS/bBc1Iug==&bucket=pdf&archivo={$json_s3->archivo}");
                                    if($res_read->getBody()){
                                        $stream = $res_read->getBody();
                                        $contents = $stream->getContents(); 
                                        if(!empty($contents)){
                                            if(stripos($contents, $fullname)){
                                                if(stripos($contents, $rut_d)){
                                                    if(stripos($contents, date('Y'))){
                                                        $water_message = 'Se ha validado del recibo de agua exitosamente';
                                                        $db_file->verified = 1;
                                                        //$property->verified = 1;
                                                        //$property->save();
                                                        //$db_file->code = $res_code->codigo;
                                                        $w_alert = 200;
                                                    } else {
                                                        $water_message = 'El recibo del agua no se encuentra actualizado';
                                                        $db_file->verified = 3;
                                                        $w_alert = 401;
                                                    }
                                                } else {
                                                    $water_message = 'No se encuentra el rut del propietario en el recibo de agua';
                                                    $db_file->verified = 3;
                                                    $w_alert = 401;
                                                }
                                            } else {
                                                $water_message = 'No se encuentra el propietario en el recibo de agua';
                                                $db_file->verified = 3;
                                                $w_alert = 401;
                                            }
                                        } else {
                                            $water_message = 'No se encuentro contenido en el archivo';
                                            $db_file->verified = 3;
                                            $w_alert = 401;
                                        }
                                    } else {
                                        $water_message = 'No se encuentro cuerpo en el archivo';
                                        $db_file->verified = 3;
                                        $w_alert = 401;
                                    }
                                } else {
                                    $water_message = 'No se encuentro variable archivo';
                                    $db_file->verified = 3;
                                    $w_alert = 401;
                                }
                            } else {
                                $water_message = 'No se encuentro cuerpo en el archivo';
                                $db_file->verified = 3;
                                $w_alert = 401;
                            }
                            $last_water_bill = $db_file->verified;
                            $db_file->save();
                        } else {
                            $water_message = 'No se encuentra archivo de ultimo recibo de agua por favor cargue uno para validarlo';
                            $w_alert = 401;
                        }
                    } else {
                        if($file->verified == 1){
                            $water_message = 'El ultimo recibo de agua ya ha sido verificado anteriormente';
                            $w_alert = 200;
                        } else {
                            $water_message = 'El ultimo recibo de agua ya ha sido verificado anteriormente pero no cumplio con los requisitos por favor modifica el archivo para poder realizar otra validación';
                            $w_alert = 401;
                        }
                    }
                    break;
                default: 
                    
                    break;
            }
            
            /**/
            if($property_certificate == 1 && $last_electricity_bill == 1 && $last_water_bill == 1){
                $property->verified = 1;
                $pproperty = $property->verified;
                $property->save();
                $alert = 200;
            } else {
                $pproperty = 0;
                $alert = 401;
            }
        }
        return response([
            'certificade' => $property_certificate,
            'last_electricity_bill' => $last_electricity_bill,
            'last_water_bill' => $last_water_bill,
            'p_verified' => $pproperty,
            'water_message' => $water_message,
            'elect_message' => $elect_message,
            'cert_message' => $cert_message,
            'c_alert' => $c_alert,
            'e_alert' => $e_alert,
            'w_alert' => $w_alert,
            'alert' => $alert
        ]);
    }
    public function applyManagement(Request $request){
        
        if($user = \Auth::user()){
            $user_property = UserProperty::where('user_id', $user->id)->where('type', 1)->get();

            foreach($user_property as $prop){
                $property = Property::find($prop->property_id);
                $property->manage = $request->manage;
                $property->save();
            }

            return response(['message' => 'Se han cambiado opción de gestion de todas tus propiedades exitosamente'],200);
        }
    }
    public function getUnavailableDays(Request $request){
        if( $user = \Auth::user()){
            $p = Property::findOrFail($request->property_id);
            $apply_property = \App\ApplyProperty::where('property_id', $p->id)->where('state', 3)->get();
            $postulations = [];
            foreach ($apply_property as $value) {
                $postulation_dates = DB::table('users_has_postulates_days')->where('id', $value->id)->first();
                array_push($postulations, $postulation_dates->days);
            }
            $disables = json_decode($p->schedule_dates);
            
             
            return json_encode(array('postulations' => $postulations, 'disables' => $disables));
            /*$decoded = json_decode($p->schedule_dates);
            return json_encode($decoded);
            return response()->json($decoded);*/
        } else{
            return response('Debes estar logueado para poder ver estos datos', 244);
        }
    }
}
