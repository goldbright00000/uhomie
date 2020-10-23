<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
	/**
		Class Constraints
	*/
	const ID_FILES_TYPE = 1; /* ID Documents */
	const JOB_FILES_TYPE = 2; /* Job Files */
	const FIN_FILES_TYPE = 3; /* Fin Files */
	const PROPERTIES_FILES_TYPE = 4; /* Properties Files */
	const VIDEO_FILES_TYPE = 5;

	const USERS_URI = '/users/';
	const PROPERTIES_URI = '/properties/';
	const ID_DOCUMENT_FRONT = "id_front";
	const ID_DOCUMENT_BACK = "id_back";
	const PASSPORT = 'passport';
	const ID_DOCUMENTS = array(self::ID_DOCUMENT_FRONT, self::ID_DOCUMENT_BACK);
	const VIDEOSELFIE = "video_selfie";
	const F_SETTLEMENT = "first_settlement";
	const S_SETTLEMENT = "second_settlement";
	const T_SETTLEMENT = "third_settlement";
	const WORK_CONSTANCY = "work_constancy";
	const AFP = "afp";
	const DICOM = "dicom";
	const OTHER_INCOME = "other_income";
	const LAST_INVOICE = "last_invoice";
	const SAVES = "saves";

	const LAST_E_BILL = 'last_electricity_bill';
	const LAST_W_BILL = 'last_water_bill';
	const CE_RECEIPT = 'common_expense_receipt';
	const PROPERTY_CERTIFICATE = 'property_certificate';

	const BUILDING_PROPERTY_CONTRACT = 'building_property_contract';
	const PROPERTY_ROOM_CONDITIONS = 'property_room_conditions';
	const REGULATION_RULES = 'regulation_rules';

	const PROPERTIES_DOCUMENTS = array(self::LAST_E_BILL, self::LAST_W_BILL, self::CE_RECEIPT, self::PROPERTY_CERTIFICATE);
	const ROOM_DOCUMENTS = array(self::LAST_E_BILL, self::LAST_W_BILL);
	const OFFICE_DOCUMENTS = array(self::BUILDING_PROPERTY_CONTRACT,self::PROPERTY_ROOM_CONDITIONS,self::REGULATION_RULES);
	const EMPLOYEE_DOCUMENTS = array(self::F_SETTLEMENT, self::S_SETTLEMENT, self::T_SETTLEMENT, self::AFP, self::WORK_CONSTANCY);
	const UNEMPLOYEE_DOCUMENTS = array(self::F_SETTLEMENT, self::S_SETTLEMENT, self::T_SETTLEMENT);

	/**
		Fillables and Model Config
	*/
	public $timestamps = false;

	protected $fillable = [
		'name', 'original_name', 'type', 'path', 'verified', 'user_id', 'property_id', 'company_id', 's3', 'id_videoindexer', 'thumbnail', 'verified_ocr'
	];
	/**
		ORM Relationships
	*/

	public function user()
	{
		return $this->belongsTo('App\User');
	}
	public function property()
	{
		return $this->belongsTo('App\Property');
	}
	public function company()
	{
		return $this->belongsTo('App\Company');
	}

	/**
		Parent Override Methods
	*/
	public function delete(){
		try {
			if ($this->user_id) {
				Storage::delete(self::USERS_URI.$this->user_id.'/files/'.$this->name);
			}elseif ($this->property_id) {
				Storage::delete(self::PROPERTIES_URI.$this->property_id.'/files/'.$this->name);
			}
		} catch (Exception $e) {

		}
		parent::delete();
	}

	/**
		Class Methods
	*/
	public static function generateFile($user, $file, $file_type = null){
		$f = $user->files()->where('name', $file)->first();
		if (is_null($f)) {
			self::create([
				'name' => $file,
				'type' => $file_type,
				'user_id' => $user->id
			]);
		}
		return ;
	}
	public static function generateCompanyFile($company, $file, $file_type = null){
		$f = $company->files()->where('name', $file)->first();
		if (is_null($f)) {
			self::create([
				'name' => $file,
				'type' => $file_type,
				'company_id' => $company->id
			]);
		}
		return ;
	}

	public static function generatePropFile($property, $file, $file_type){
		$f = $property->files()->where('name', $file)->first();
		if (is_null($f)) {
			self::create([
				'name' => $file,
				'type' => $file_type,
				'property_id' => $property->id
			]);
		}
		return ;
	}

	public static function generateIdFiles($user){
		foreach (self::ID_DOCUMENTS as $d) {
			self::generateFile($user, $d, self::ID_FILES_TYPE);
		}
	}

	public static function deleteJobFiles($user){
		foreach ($user->files()->where('type', self::JOB_FILES_TYPE)->get() as $f) {
			$f->delete();
		}
	}

	public static function generateEmployeeFiles($user){

		foreach (self::EMPLOYEE_DOCUMENTS as $d) {
			self::generateFile($user, $d, self::JOB_FILES_TYPE);
		} /*TODO: Mejorar Lógica*/
	}

	public static function generateUnemployeeFiles($user){

		foreach (self::UNEMPLOYEE_DOCUMENTS as $d) {
			self::generateFile($user, $d, self::JOB_FILES_TYPE);
		} /*TODO: Mejorar Lógica*/
	}
	public function getPublicUrl(){
		return env('APP_URL').'/get-file/?path='.$this->id;
	}

	public static function generatePropertyFiles($property){
		foreach (self::PROPERTIES_DOCUMENTS as $d) {
			self::generatePropFile($property, $d, self::PROPERTIES_FILES_TYPE);
		}
	}
	public static function generateOfficeFiles($property){
		foreach (self::OFFICE_DOCUMENTS as $d) {
			self::generatePropFile($property, $d, self::PROPERTIES_FILES_TYPE);
		}
	}
	public static function generateRoomFiles($property){
		foreach (self::ROOM_DOCUMENTS as $d){
			self::generatePropFile($property, $d, self::PROPERTIES_FILES_TYPE);
		}
	}
}
