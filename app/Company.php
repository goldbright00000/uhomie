<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\{File, Photo};
use Carbon\Carbon;

class Company extends Model
{
	const SERVICE_TYPE = 1;
	const AGENT_TYPE = 0;
	/**
		Class Constraints
	*/

	public $timestamps = false;
	/**
		Fillables and Model Config
	*/
	protected $fillable = [
    'name','phone','cell_phone','website','email','rut','giro','type','address' , 'address_details' , 'latitude' , 'longitude'
  ];
  /**
    ORM Relationships
  */
  public function servicesList()
  {
    return $this->belongsToMany('App\ServiceList','companies_has_services_list', 'company_id', 'service_list_id');
  }

	public function files()
  {
      return $this->hasMany('App\File');
  }
  public function photos()
  {
	  return $this->hasMany('App\Photo');
  }
  public function user()
  {
      return $this->belongsTo('App\User');
  }
  public function city()
  {
      return $this->belongsTo('App\City');
  }
	public function properties()
  {
      return $this->hasMany('App\Property');
  }

	public function projects()
	{
		return $this->hasMany('App\Project');
	}

  public function saveBasicData($request, $type = true){
	$id = $request->userId ? $request->userId : \Auth::user()->id;
	$request->phone = str_replace("-","",$request->phone);
	$request->cell_phone = str_replace(["(",")","-"],"",$request->cell_phone);
	$this->name = $request->name;
	$this->invoice = $request->invoice;
	$this->rut = $request->rut;
	$this->giro = $request->giro;
	$this->phone = $request->phone;
	$this->cell_phone = $request->cell_phone;
	$this->email = $request->email;
	$this->website = $request->website;
	$this->user_id = $id;
	$this->type = $type ? true : false;
	$files = $request->files->all();
	$this->save();
	foreach ($files as $fileName => $file) {
		 try {
			 if (in_array($fileName, File::ID_DOCUMENTS)) {
			 	$db_file = $this->files()->where('name', $fileName)->first();
				 if (is_null($db_file)) {
					 File::generateCompanyFile($this, $fileName);
				 }
				 $db_file = $this->files()->where('name', $fileName)->first();
				 \Storage::disk('local')->putFileAs('companies/'.$this->id.'/files/',$file,$fileName);
				 $db_file->original_name = $file->getClientOriginalName();
				 $db_file->path = 'companies/'.$this->id.'/files/'.$fileName;
				 $db_file->save();
			 }else {
				 $db_file = $this->photos()->where(['cover' => true , 'logo' => true])->first();
				 if ($db_file) {
					 $db_file->delete();
				 }
				 $db_file = new Photo;
				 $date_uuid = Carbon::now()->format('Ymdhmsu');
				 $db_file->name = 'photo_'.$fileName.'-'.$date_uuid. '.' . $file->getClientOriginalExtension();
				 $db_file->cover = true;
				 $db_file->logo = true;
				 \Storage::disk('local')->putFileAs('public/companies/'.$this->id.'/photos/',$file,$db_file->name);
				 $db_file->company_id = $this->id;
				 $db_file->original_name = $file->getClientOriginalName();
				 $db_file->path = '/storage/companies/'.$this->id.'/photos/'.$db_file->name;

				 $db_file->save();
			 }
		 } catch (Exception $e) {
		 }
	 }
  }

}
