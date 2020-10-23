<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ContractDocument;
use App\User;
use App\Property;

class Contract extends Model
{
	use ContractDocument;
	/**
		Class Constraints
	*/

	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'tenant_sign',
		'owner_sign',
		'collateral_sign',
		'rent',
		'months_advance',
		'tenanting_months',
		'warranty_months',
		'move_date',
		'hellosign_id',
		'path_file',
		'path_file_pre',
		/*
		'signature_request_id',
		'is_complete',
		'',
		*/
	];
	/**
		ORM Relationships
	*/
	/*
	public function property()
	{
		return $this->belongsToMany('App\Property', 'users_has_properties', 'property_id', 'user_id')->withPivot('type');
	}
	*/

	public function generarContratoConAval(){
		//return $this->toPdfWithAval();
		$owner = $this->getOwnerFromContract();
		$tenant = $this->getTenantFromContract();
		$collateral = $this->getCollateralFromContract();
		$property = $this->getPropertyFromContract();
		return $this->toPdfWithAvalBeta($owner, $tenant, $collateral, $property);
	}
	
	public function generarContratoStream(){
		return $this->toStream($this->path_file, $this->id);
	}

	public function generarContratoSinAval(){

		$owner = $this->getOwnerFromContract();
		$tenant = $this->getTenantFromContract();
		$property = $this->getPropertyFromContract();
		
		return $this->toPdfWithoutAval($owner, $tenant, $property);
		
	}
	public function getOwnerFromContract(){
		foreach($this->users as $user){
			if($user->pivot->signer_role == 'owner') return $user;
		}
	}
	public function getTenantFromContract(){
		
		foreach($this->users as $user){
			if($user->pivot->signer_role == 'tenant') return $user;
		}
		
		//return User::find(13);
        
	}
	public function getCollateralFromContract(){
		
		foreach($this->users as $user){
			if($user->pivot->signer_role == 'collateral'){
				return $user;
			}
		}
		
		
        //return User::find(1);
	}
	public function getPropertyFromContract(){
        return $this->property()->first();
	}
	
	public function users()
	{
		return $this->belongsToMany('App\User', 'users_has_contracts', 'contract_id', 'user_id')
					->withPivot('order', 
								'status_code', 
								'signature_id', 
								'signer_email_address', 
								'signer_name', 
								'signed_at', 
								'last_viewed_at', 
								'last_reminded_at', 
								'has_pin', 
								'signer_pin', 
								'signer_role', // Importante campo identificatorio de perfil
								'ip_address',
								'auditory_file' )
					->withTimestamps();
	}

	public function property()
	{
		return $this->belongsTo('App\Property');
	}


	public function payment()
	{
		return $this->hasOne('App\Payment');
	}
	public function areEveryoneVerifiedVin(){
		$flag = true;
		foreach( $this->users()->get() as $user ){
			if( $videoselfie = $user->files()->where('type', File::VIDEO_FILES_TYPE)->first() ){
				if( !$videoselfie->thumbnail ){
					$flag = false;
				}
			} else {
				$flag = false;
			}
			
		}
		return $flag;
	}
	public function isUserContract($id)
	{
		$flag = true;
		foreach( $this->users()->get() as $user ){
			if( $user->id == $id ){
				$flag = true;
			}
		}
		return $flag;
	}

	public static function generarContratoBorrador($owner, $tenant, $property)
	{
		return $this->streamBorrador($owner, $tenant, $property);
	}
}
