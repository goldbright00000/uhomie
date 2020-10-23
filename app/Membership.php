<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use Carbon\Carbon;

class Membership extends Model
{
	/**
	Class Constraints
	*/
	const TYPE_TENANT = 1;
	const TYPE_OWNER = 2;
	const TYPE_AGENT = 3;
	const TYPE_SERVICE = 4;
	const TYPE_COLLATERAL = 5;

	const DEFAULT_STATUS = false;
	const OWNER_CONTACT_CHAT = 0, OWNER_CONTACT_CHAT_EMAIL = 1, OWNER_CONTACT_CHAT_EMAIL_PHONE = 2;
	const RECOMMENDATIONS_MESSAGES = [null, 'Mailing', 'Mailing, Campañas,Push de Notificaciones, Redes sociales'];
	/**
	Fillables and Model Config
	*/
	public $timestamps = false;
	protected $fillable = [
		'name', 'features', 'enabled'
	];
	/**
	ORM Relationships
	*/

	public function role()
	{
		return $this->belongsTo('App\Role');
	}

	public function users()
	{
		return $this->belongsToMany('App\User', 'users_has_memberships', 'membership_id', 'user_id')->withPivot('purchased_at', 'expires_at');
	}


	/**
	Class Methods
	*/
	public static function getDefaultMembership($role_id){
		return self::where([
			['enabled', self::DEFAULT_STATUS],
			['role_id', $role_id]
			])->first();
	}

	/**
	* Get Membership features as array
	**/
	public function getFeatures(){
		return json_decode($this->features);
	}

	public function hasTrustSeal(){
		return $this->getFeatures()->trust_seal;
	}

	public function getOwnerContactAttribute(){
		$oc = $this->getFeatures()->owner_contact;
		switch ($oc) {
			case self::OWNER_CONTACT_CHAT:
			return "Chat";
			break;
			case self::OWNER_CONTACT_CHAT_EMAIL:
			return "Correo + Chat";
			break;
			case self::OWNER_CONTACT_CHAT_EMAIL_PHONE:
			return "Telefono + Correo + Chat";
			break;
		}
	}

	public function getRecommendationMessageAttribute(){
		return self::RECOMMENDATIONS_MESSAGES[$this->getFeatures()->recommendations];
	}

	public static function getTenantMemberships(){
		return self::where(['role_id' => self::TYPE_TENANT, 'enabled' => true])->get();
	}
	public static function getOwnerMemberships(){
		return self::where(['role_id' => self::TYPE_OWNER, 'enabled' => true])->get();
	}
	public static function getAgentMemberships(){
		return self::where(['role_id' => self::TYPE_AGENT, 'enabled' => true])->get();
	}
	public static function getServiceMemberships(){
		return self::where(['role_id' => self::TYPE_SERVICE, 'enabled' => true])->get();
	}
	public static function getCollateralMembership(){
		return self::where(['role_id' => self::TYPE_COLLATERAL])->first();
	}

	public static function checkAgentMemberships(){
		$sc = false;
		if(\Auth::user()){
			$m = self::getAgentMemberships();
			foreach ($m as $mem) {
				if (\Auth::user()->memberships->contains($mem)) {
					$sc = true;
					break;
				}
			}
		}
		return $sc;
	}
	public static function checkServiceMemberships(){
		$sc = false;
		if(\Auth::user()){
			$m = self::getServiceMemberships();
			foreach ($m as $mem) {
				if (\Auth::user()->memberships->contains($mem)) {
					$sc = true;
					break;
				}
			}
		}
		return $sc;
	}

	public static function checkOwnerMemberships(){
		$sc = false;
		if(\Auth::user()){
			$m = self::getOwnerMemberships();
			foreach ($m as $mem) {
				if (\Auth::user()->memberships->contains($mem)) {
					$sc = true;
					break;
				}
			}
		}
		return $sc;
	}

	public static function checkTenantMemberships(){
		$sc = false;
		if(\Auth::user()){
			$m = self::getTenantMemberships();
			foreach ($m as $mem) {
				if (\Auth::user()->memberships->contains($mem)) {
					$sc = true;
					break;
				}
			}
		}
		return $sc;
	}

	public static function attachMembership($membership_id, $user){
		$membership = self::find($membership_id);
		
		foreach ($user->memberships as $m) {
			if ($m->role_id == $membership->role_id) {
				switch($m->role_id){
					case '3':
						if($m->pivot->membership_id == $membership->id){
							$fecha_actual = Carbon::now();
							$fecha_expiracion = Carbon::parse(substr($m->pivot->expires_at, 0, 10));
							if($fecha_expiracion->greaterThan($fecha_actual)){
								$expires_at = Carbon::parse($fecha_expiracion)->addDays(intval(json_decode($membership->features)->project_due_days));
							} else {
								$expires_at = Carbon::now()->addDays(intval(json_decode($membership->features)->project_due_days));
							}
						} else {
							$expires_at = Carbon::now()->addDays(intval(json_decode($membership->features)->project_due_days));
						}
						break;
					case '4':
						if($m->pivot->membership_id == $membership->id){
							$fecha_actual = Carbon::now();
							$fecha_expiracion = Carbon::parse(substr($m->pivot->expires_at, 0, 10));
							if($fecha_expiracion->greaterThan($fecha_actual)){
								$expires_at = Carbon::parse($fecha_expiracion)->addDays(intval(json_decode($membership->features)->project_due_days));
							} else {
								$expires_at = Carbon::now()->addDays(intval(json_decode($membership->features)->project_due_days));
							}
						} else {
							$expires_at = Carbon::now()->addDays(intval(json_decode($membership->features)->project_due_days));
						}
						break;
					default:
						if($m->pivot->membership_id == $membership->id){
							$fecha_actual = Carbon::now();
							$fecha_expiracion = Carbon::parse(substr($m->pivot->expires_at, 0, 10));
							if($fecha_expiracion->greaterThan($fecha_actual)){
								$expires_at = Carbon::parse($fecha_expiracion)->addDays(30);
							} else {
								$expires_at = Carbon::now()->addDays(30);
							}
						} else {
							$expires_at = Carbon::now()->addDays(30);
						}
						break;
				}
				$user->memberships()->detach($m->id);
			}
		} #TODO: FIX THIS ROUTINE
		$user->memberships()->attach($membership->id, array('expires_at' =>  $expires_at, 'purchased_at' => Carbon::now()));
		if ($user->{substr($membership->role->slug, 0, -1).'_profile_redirect'}) {
			$user->generateProfileNotification($membership->role->name);
		}
	}
}
