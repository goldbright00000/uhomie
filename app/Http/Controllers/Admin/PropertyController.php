<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\{Property,PropertyType,PropertyFor,File,User, Photo};
use Image;
class PropertyController extends Controller
{
    public function getProperties( Request $request ){
      $db_properties = Property::where(['is_project' => false])->get();
      $records = [];
      foreach ($db_properties as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = 'Desconocido: '.$p->status;
            break;
        }

        if(!is_null($p->getOwner())){
          $owner_id = $p->getOwner()->id;
          $owner_name = 'Propietario - '.$p->getOwner()->lastname.', '.$p->getOwner()->firstname;
          $owner_email = $p->getOwner()->email;
        } elseif (!is_null($p->getAgent())) {
          $owner_id = $p->getAgent()->id;
          $owner_name = 'Agente - '.$p->getAgent()->lastname.', '.$p->getAgent()->firstname;
          $owner_email = $p->getAgent()->email;
        } else {
          $owner_id = 'Problema';
          $owner_name = 'Problema';
          $owner_email = 'Problema';
        }

        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "active" => $p->active,
          "slug" => strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $p->name))),
          "status" => $p->status,
          "status_name" => $status,
          "views" => $p->views,
          "updated_at" => !is_null($p->updated_at) ? $p->updated_at->format('d-m-Y H:i') : '',
          "owner_id" => $owner_id,
          "owner_name" => $owner_name,
          "owner_email" => $owner_email
        ];
      }
      return response([ 'records' => $records ]);
  	}

    /*
    * Este controlador es una copia exacta del front PropertyController@savePhoto
    */
  public function savePhoto(Request $request){

    //dd($request->file('files'));

    /*$property = Property::find($request->property_id);

        $db_file = Photo::find($request->photo_id);      

        if ($db_file) {
            $db_file->delete();
        }
        $db_file = new Photo;
    foreach ($files as $file) {                   
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
    }*/



    if ($request->file('files')) {
      $property = Property::find($request->property_id);

      $date_uuid = Carbon::now()->format('Ymdhmsu');
      $db_file = Photo::find($request->photo_id);
      if ($db_file) {
        if (Storage::exists(str_replace('storage', 'public',$db_file->path))){
            Storage::delete(str_replace('storage', 'public',$db_file->path));
        }
        $db_file->delete();
      }

      $db_file = new Photo;
      $photo_name = 'photo_'.explode('.',$request->file('files')->getClientOriginalName())[0].'-'.$date_uuid.'.jpg';
      
      $originalImage= $request->file('files');
      $thumbnailImage = Image::make($originalImage);
      $originalPath = public_path().'/storage/properties/'.$property->id.'/photos/';
      $width = $thumbnailImage->width();
      $height = $thumbnailImage->height();
      if($width > $height){
          $thumbnailImage->resize(null,1024, function ($constraint) {
              $constraint->aspectRatio();
          });
      } else {
          $thumbnailImage->resize(1024,null, function ($constraint) {
              $constraint->aspectRatio();
          });
      }
      if($request->rotate > 0 || $request->rotate < 0){
        $thumbnailImage->rotate($request->rotate);
      }
      
      $thumbnailImage->save($originalPath.$photo_name);
      $photo_dir = '/storage/properties/'.$property->id.'/photos/'.$photo_name;
      $db_file->cover = $request->cover;
      $db_file->name = $photo_name;
      $db_file->path = $photo_dir;
      $db_file->space_id = $request->space_id;
      $db_file->property_id = $property->id;
      $db_file->save();
      return response()->json(['code' => 200, 'url' => $db_file->name, "path" => $db_file->path, "photo_id" => $db_file->id, "space_id" => $db_file->space_id, 'description' => 'Image Updated']);
    } else {
      return response()->json(['code'=>400, 'description' => 'File Not Found']);
    }
    return response(["path" => $db_file->path, "photo_id" => $db_file->id, "space_id" => $db_file->space_id]);
  }

    /*
    * Listado de propiedades publicadas
    */
    public function getPropertiesPublished( Request $request ){
      $db_properties = Property::where(['is_project' => false, 'status' => 0])->get();
      $records = [];
      foreach ($db_properties as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = 'Desconocido: '.$p->status;
            break;
        }
        if(!is_null($p->getOwner())){
          $owner_id = $p->getOwner()->id;
          $owner_name = 'Propietario - '.$p->getOwner()->lastname.', '.$p->getOwner()->firstname;
          $owner_email = $p->getOwner()->email;
        } elseif (!is_null($p->getAgent())) {
          $owner_id = $p->getAgent()->id;
          $owner_name = 'Agente - '.$p->getAgent()->lastname.', '.$p->getAgent()->firstname;
          $owner_email = $p->getAgent()->email;
        } else {
          $owner_id = 'Problema';
          $owner_name = 'Problema';
          $owner_email = 'Problema';
        }
        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "status" => $p->status,
          "status_name" => $status,
          "views" => $p->views,
          "updated_at" => !is_null($p->updated_at) ? $p->updated_at->format('d-m-Y H:i') : '',
          "owner_id" => $owner_id,
          "owner_name" => $owner_name,
          "owner_email" => $owner_email
        ];
      }
      return response([ 'records' => $records ]); 
    }
    /*
    * Listado de propiedades Arrendadas
    */
    public function getPropertiesLeased( Request $request ){
      $db_properties = Property::where(['is_project' => false, 'status' => 2])->get();
      $records = [];
      
      foreach ($db_properties as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = 'Desconocido: '.$p->status;
            break;
        }
        if(!is_null($p->getOwner())){
          $owner_id = $p->getOwner()->id;
          $owner_name = 'Propietario - '.$p->getOwner()->lastname.', '.$p->getOwner()->firstname;
          $owner_email = $p->getOwner()->email;
        } elseif (!is_null($p->getAgent())) {
          $owner_id = $p->getAgent()->id;
          $owner_name = 'Agente - '.$p->getAgent()->lastname.', '.$p->getAgent()->firstname;
          $owner_email = $p->getAgent()->email;
        } else {
          $owner_id = 'Problema';
          $owner_name = 'Problema';
          $owner_email = 'Problema';
        }
        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "status" => $p->status,
          "status_name" => $status,
          "views" => $p->views,
          "updated_at" => $p->updated_at->format('d-m-Y H:i'),
          "owner_id" => $owner_id,
          "owner_name" => $owner_name,
          "owner_email" => $owner_email
        ];
      }
      return response([ 'records' => $records ]);
    }

    public function getPropertiesExecutive( Request $request ){
      $db_properties = Property::where(['is_project' => false, 'executive_id' => \Auth::user()->id])->get();
      $records = [];
      
      foreach ($db_properties as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = 'Desconocido: '.$p->status;
            break;
        }
        if(!is_null($p->getOwner())){
          $owner_id = $p->getOwner()->id;
          $owner_name = 'Propietario - '.$p->getOwner()->lastname.', '.$p->getOwner()->firstname;
          $owner_email = $p->getOwner()->email;
        } elseif (!is_null($p->getAgent())) {
          $owner_id = $p->getAgent()->id;
          $owner_name = 'Agente - '.$p->getAgent()->lastname.', '.$p->getAgent()->firstname;
          $owner_email = $p->getAgent()->email;
        } else {
          $owner_id = 'Problema';
          $owner_name = 'Problema';
          $owner_email = 'Problema';
        }
        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "status" => $p->status,
          "status_name" => $status,
          "views" => $p->views,
          "updated_at" => $p->updated_at->format('d-m-Y H:i'),
          "owner_id" => $owner_id,
          "owner_name" => $owner_name,
          "owner_email" => $owner_email
        ];
      }
      return response([ 'records' => $records ]);
    }

    /*
    * Change Cover or Spaces in photo
    */
    public function changeMetaPhoto(Request $request){
      $photo = Photo::find($request->photo_id);
      
      if ( !is_null($photo) ) {
        
        
        if($request->rotate > 0 || $request->rotate < 0){
          if($request->rotate > 0) {
            $rotate = -$request->rotate;
          } else {
            $rotate = abs($request->rotate);
          }
          $thumbnailImage = Image::make(Storage::disk('public')->get('properties/'.$request->property_id.'/photos/'.$photo->name))->rotate($rotate);
          if ($photo) {
            if (Storage::exists(str_replace('storage', 'public',$photo->path))){
                Storage::delete(str_replace('storage', 'public',$photo->path));
            }
          }
          $originalPath = public_path().'/storage/properties/'.$request->property_id.'/photos/';
          //Storage::disk('public')->put('properties/'.$request->property_id.'/photos/'.$photo->name, $thumbnailImage);
          $thumbnailImage->save($originalPath.$photo->name);
        }
        
        $photo->space_id = $request->space_id;
        $photo->cover = $request->cover ? '1' : '0';
        $photo->save();
        return response(['code' => 200,"path" => $photo->path, "photo_id" => $photo->id]);
      }
    }

    /*
    * Listado de propiedades pausadas
    */
    public function getPropertiesPaused( Request $request ){
      $db_properties = Property::where(['is_project' => false, 'status' => 1 ])->get();
      $records = [];
      
      foreach ($db_properties as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = 'Desconocido: '.$p->status;
            break;
        }
        if(!is_null($p->getOwner())){
          $owner_id = $p->getOwner()->id;
          $owner_name = 'Propietario - '.$p->getOwner()->lastname.', '.$p->getOwner()->firstname;
          $owner_email = $p->getOwner()->email;
        } elseif (!is_null($p->getAgent())) {
          $owner_id = $p->getAgent()->id;
          $owner_name = 'Agente - '.$p->getAgent()->lastname.', '.$p->getAgent()->firstname;
          $owner_email = $p->getAgent()->email;
        } else {
          $owner_id = 'Problema';
          $owner_name = 'Problema';
          $owner_email = 'Problema';
        }
        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "status" => $p->status,
          "status_name" => $status,
          "views" => $p->views,
          "updated_at" => $p->updated_at->format('d-m-Y H:i'),
          "owner_id" => $owner_id,
          "owner_name" => $owner_name,
          "owner_email" => $owner_email
        ];
      }
      return response([ 'records' => $records ]);
    }
    /*
    * Listado de propiedades eliminadas
    */
    public function getPropertiesEliminated( Request $request ){
      $db_properties =  DB::table('properties')
          ->select('properties.*', 'users.id as owner_id', 'users.firstname', 'users.lastname')
          ->leftJoin('users_has_properties', 'properties.id', '=', 'users_has_properties.property_id')
          ->leftJoin('users', 'users_has_properties.user_id', '=', 'users.id')
          ->whereNotNull('properties.deleted_at')->get();      

      $records = [];
      
      foreach ($db_properties as $p) {
        switch ($p->status) {
          case 0:
            $status = 'Publicada';
            break;
          case 1:
            $status = 'Pausada';
            break;
          case 2:
            $status = 'Arrendada';
            break;
          case 3:
            $status = 'Cancelada';
            break;
          default:
            $status = 'Desconocido: '.$p->status;
            break;
        }
        $owner_id = 'No disponible';
        $owner_name = 'No disponible';
        $owner_email = 'No disponible';
        $records[] = (object)[
          "id" => $p->id,
          "name" => $p->name,
          "status" => $p->status,
          "status_name" => $status,
          "views" => $p->views,
          "updated_at" => $p->updated_at,
          "owner_id" => $owner_id,
          "owner_name" => $owner_name,
          "owner_email" => $owner_email
        ];
      }
      return response([ 'records' => $records ]);
    }

    public function deletePhoto(Request $request){

      $photo = Photo::find($request->photo_id);

      if ( !is_null($photo) ) {
        $photo->delete();
        return response( [ "operation" => true ] );
      }
      return response( [ "operation" => false ] );
    }

    public function getProperty( Request $request ){

      //$property = Property::find($request->propertyId);

      $property = Property::with(['photos.space'])->where('id', $request->propertyId)->first();


      return response([
        "property" => $property,
        "owner" => !is_null($property->getOwner()) ? $property->getOwner()->only("id","firstname","lastname","email") : '',
        "property_type" => $property->propertyType,
        "properties_for" => $property->propertiesFor,
        "files" => $property->files
      ]);
    }
    public function deleteProperty( Request $request ){
  		$property = Property::find($request->propertyId);
      $property->delete();
      return response([ "operation" => true ]);
  	}
    public function getPropertiesFor(){
  		$properties_for = PropertyFor::all();
      return response([ "records" => $properties_for ]);
  	}
    public function getPropertyTypes(){
  		$property_types = PropertyType::where("enabled",true)->get();
      return response([ "records" => $property_types ]);
    }
    public function saveBasicData( Request $request ){
  		$user = $request->userId ? User::find($request->userId) : null;
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      if (is_null($property)) { $property = new Property(); }
      $property->name = $request->name;
      $property->property_type_id = $request->property_type;
      $property->condition = $request->condition;
      $property->description = $request->description;
      $property->save();
      File::generatePropertyFiles($property);
      if ($property->users()->where(['type' => Property::TYPE_OWNER])->count() == 0)  {
        $property->users()->attach($request->userId, ['type' => Property::TYPE_OWNER]);
      }else{
        $property->users()->where(['type' => Property::TYPE_OWNER])->detach();
        $property->users()->attach($request->userId, ['type' => Property::TYPE_OWNER]);
      }
      return response(['success' => true, 'property' => $property ]);
    }
    public function saveLocationData( Request $request ){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      $property->city_id = $request->city;
      $property->address = $request->address;
      $property->address_details = $request->address_details;
      $property->latitude = $request->latitude;
      $property->longitude = $request->longitude;
      $property->save();
      return response([ 'success' => true ]);
    }
    public function savePropertyDetails( Request $request ){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      $property->pool = $request->pool;
      $property->garden = $request->garden;
      $property->bedrooms = $request->bedrooms;
      $property->bathrooms = $request->bathrooms;
      $property->terrace = $request->terrace;
      $property->meters = $request->meters;
      $property->private_parking = $request->private_parking;
      $property->public_parking = $request->public_parking;
      $property->save();
      return response([ 'success' => true ]);
    }
    public function saveTenantingConditions( Request $request ){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      isset($request->rent) ? $property->rent = str_replace(".","",$request->rent) : $property->rent = 0;
      isset($request->common_expenses_limit) ? $property->common_expenses_limit = str_replace(".","",$request->common_expenses_limit) : $property->common_expenses_limit = 0;
      $property->tenanting_insurance = $request->tenanting_insurance;
      $property->warranty_months_quantity = $request->warranty_months_quantity;
      $property->months_advance_quantity = $request->months_advance_quantity;
      $available_date = str_replace('/', '-', $request->available_date);
      $property->available_date = date('Y-m-d', strtotime($available_date));
      $property->tenanting_months_quantity = $request->tenanting_months_quantity;
      $property->collateral_require = $request->collateral_require;
      $property->furnished = $request->furnished;
      $property->furnished_description = $request->furnished_description;
      $property->save();
      return response([ 'success' => true ]);
    }
    public function saveVisitPreferences( Request $request ){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      $property->visit = $request->visit;
      if ($request->visit) {
        $visit_from_date = str_replace('/', '-', $request->visit_from_date);
        $property->visit_from_date = date('Y-m-d', strtotime($visit_from_date));
        $visit_to_date = str_replace('/', '-', $request->visit_to_date);
        $property->visit_to_date = date('Y-m-d', strtotime($visit_to_date));
        $property->schedule_range = $request->schedule_range;
      }else{
        $property->visit_from_date = null;
        $property->visit_to_date = null;
        $property->schedule_range = null;
      }
      $property->save();
      return response([ 'success' => true ]);
    }
    public function saveFeaturesTenant( Request $request ){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      $property->pet_preference = $request->pet_preference;
      $property->smoking_allowed = $request->smoking_allowed;
      $property->nationals_with_rut = $request->nationals_with_rut;
      $property->foreigners_with_rut = $request->foreigners_with_rut;
      $property->foreigners_with_passport = $request->foreigners_with_passport;
      $property->propertiesFor()->sync($request->property_for);
      $property->save();
      return response([ 'success' => true ]);
    }

    public function savePropertyVerified(Request $request){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      $property->verified = $request->verified;
      $property->save();
      return response([ 'success' => true ]);
    }

    public function saveAssingExecute( Request $request ){
      $property = $request->propertyId ? Property::find($request->propertyId) : null;
      $property->executive_id = $request->executive_id;
      $property->save();
      return response([ 'success' => true ]);
    }


    /*
    * Pause property
    */
    public function pauseProperty(Request $request ){

      $property = Property::find($request->propertyId);
      $property->status = 1;
      $property->save();

      return response([ "operation" => true, "status" => 1, "status_name" => "Pausada" ]);
    }
    /*
    * Publish property
    */
    public function publishProperty(Request $request ){
      
      $property = Property::find($request->propertyId);
      $property->status = 0;
      $property->save();

      return response([ "operation" => true, "status" => 0, "status_name" => "Publicada" ]);
    }


    /* Quantities Propierties */

    public function getDescriptors() {

      $published = Property::where(['is_project' => false, 'status' => 0])->count();
      $paused = Property::where(['is_project' => false, 'status' => 1 ])->count();
      $leased = Property::where(['is_project' => false, 'status' => 2 ])->count();
      $eliminated = DB::table('properties')->whereNotNull('deleted_at')->count();

      return response([
        "published" => $published,
        "paused" => $paused,
        "leased" => $leased,
        "eliminated" => $eliminated
      ]);
    }


    /* --- */
}
