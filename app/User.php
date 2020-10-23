<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\PasswordGenerator;
use App\Property;
use App\Photo;
use App\Notifications\{AgentProfile, OwnerProfile, TenantProfile, ServiceProfile, CollateralProfile, CollateralMail, ResetPassword};
use App\{Membership, Role};
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    /**
		Class Traits
	*/
    use Notifiable;
    use PasswordGenerator;
    use SoftDeletes;

    /**
		Class Constants
	*/
    const OTHER_INCOME_TYPE = array(
        "No tengo ingresos adicionales",
        "Ingresos propios adicionales",
        "Ingresos de compañero de arriendo",
        "Esposa(o)"
    );

    const TENANT_REDIRECT_URI = 'users.tenants.select-stay';
    //const TENANT_REDIRECT_URI = 'users.tenants.first-step';
    const OWNER_REDIRECT_URI = 'users.owners.first-step';
    const SERVICE_REDIRECT_URI = 'users.services.first-step';
    const AGENT_REDIRECT_URI = 'users.agents.first-step';
    const COLLATERAL_REDIRECT_URI = 'users.collaterals.first-step';

    const EMPLOYEE_EMPLOYMENT_TYPE = 1; // employed
    const OWNER_EMPLOYMENT_TYPE = 2; // owner
    const UNEMPLOYED_EMPLOYMENT_TYPE = 3; // unemployed

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'document_type', 'document_number', 'metas', 'profile_picture', 'active',
        'birthdate', 'address', 'address_details', 'latitude', 'longitude', 'landline', 'phone', 'email', 'password',
        'social_login', 'activation_token', 'mail_verified', 'phone_verified', 'employment_details', 'expenses_limit', 'common_expenses_limit',
        'warranty_months_quantity', 'months_advance_quantity', 'tenanting_months_quantity', 'move_date', 'property_type', 'property_condition',
        'property_for', 'pet_preference', 'furnished', 'smoking_allowed', 'created_by_reference', 'confirmed_collateral', 'last_invoice_amount',
        'position', 'company', 'job_type', 'worked_from_date', 'worked_to_date', 'amount', 'saves', 'save_amount', 'afp', 'invoice','profile_redirect','bank','account_type','account_number',
        'country_id','civil_status_id','city_id','other_income_type','other_income_amount', 'tenanting_insurance', 'phone_code', 'authy_id',
        'tercera_clave', 'confirmed_action', 'personid_videoindexer', 'faceid_videoindexer', 'document_serie', 'show_welcome', 'provider', 'provider_id',
        'short_stay', 'long_stay'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'activation_token'
    ];

    protected $appends = [
        'once_role'
    ];
    /**
     * For SoftDeletes.
     *
     */
    protected $dates = ['deleted_at'];
    /**
        ORM Relationships
    */
    public function memberships()
    {
        return $this->belongsToMany('App\Membership', 'users_has_memberships', 'user_id', 'membership_id')->withPivot('purchased_at', 'expires_at');
    }
    public function payments()
    {
        return $this->hasMany('App\Payment');
    }
    public function providers(){
        return $this->belongsToMany('App\Provider', 'user_provider', 'user_id', 'provider_id')->withPivot('user_provider_id');
    }

    public function companies()
    {
        return $this->hasMany('App\Company');
    }

    public function properties()
    {
        return $this->belongsToMany('App\Property', 'users_has_properties', 'user_id', 'property_id')->withPivot('type', 'id');
    }

    public function applications() {
        return $this->hasManyThrough('App\ApplyProperty', 'App\UserProperty', null, 'id')->withoutGlobalScope(\App\Scopes\PropertyUserScope::class);
    }
    /**
    * Return User's Properties.
    *
    * @return objectArray
    */
    public function getOwnedProperties($getQb = false){
        if(!$getQb)
            return $this->properties()->where('type', Property::TYPE_OWNER)->get();
        return $this->properties()->where('type', Property::TYPE_OWNER);
    }

    /**
    * Return User's favourite Properties.
    *
    * @return objectArray
    */
    public function getFavouriteProperties(){
        return $this->properties()->with('photos')
            ->where('type',
                Property::TYPE_FAVOURITE)
			->orderBy('id', 'ASC')->get();
    }

    /**
    * Return User's applied Properties.
    *
    * @return objectArray
    */
    public function getAppliedProperties(){
  			return $this->properties()->with('photos')
                  ->where('type',
                      Property::TYPE_APPLICATION)->where('espera', 0)
  				->orderBy('id', 'ASC')->join('postulates', "users_has_properties.id", "=", "postulates.id")->get();
    }
    
    /**
    * Return User's applied Properties. ONLY FOR TENANT USER
    *
    * @return objectArray
    */
    public function getAppliedPropertiesEspera(){
        $membership = $this->memberships()->where('role_id', Membership::TYPE_TENANT)->first();
        $applied_properties = $this->applications()
            //->whereBetween('created_at', [$membership->pivot->purchased_at, $membership->pivot->expires_at])
            ->orderBy('created_at', 'desc')
            ->where('espera', 1)
            ->get();
        return $applied_properties;
        
    }
    /**
    * Return User's applied Properties. ONLY FOR TENANT USER
    *
    * @return objectArray
    */
    public function getAppliedPropertiesNoEspera(){
        $membership = $this->memberships()->where('role_id', Membership::TYPE_TENANT)->first();
        $applied_properties = $this->applications()
            //->whereBetween('created_at', [$membership->pivot->purchased_at, $membership->pivot->expires_at])
            ->orderBy('created_at', 'desc')
            ->where('espera', 0)
            ->get();
        return $applied_properties;
        
    }
    /**
     * Return User's property applycation left
     *
     * @return integer
     */
    public function getApplicationsLeftAttribute() {
        
        //if(!Membership::checkTenantMemberships()) return 0;
        
        $membership = $this->memberships()->where('role_id', Membership::TYPE_TENANT)->first();

        
        if(!$membership) return 0;

        $membership_applications = ($membership->getFeatures())->applications_received_count ?? 0;

        $applied_properties = $this->applications()
            ->whereBetween('created_at', [$membership->pivot->purchased_at, $membership->pivot->expires_at])
            ->where('espera', 0)
            ->count();
        //dd($applied_properties);
        return $membership_applications - $applied_properties;
    }

    public function amenities()
    {
        return $this->belongsToMany('App\Amenity', 'users_has_amenities', 'user_id', 'amenity_id');
    }

    public function notifications()
    {
        return $this->belongsToMany('App\Notification', 'users_has_notifications', 'user_id', 'notification_id')
            ->withPivot('active');
    }

    public function privacies()
    {
        return $this->belongsToMany('App\Privacy', 'users_has_privacies', 'user_id', 'privacy_id')
            ->withPivot('active');;
    }

    public function commune()
    {
        return $this->belongsTo('App\Commune');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
    public function civilStatus()
    {
        return $this->belongsTo('App\CivilStatus');
    }

    public function bank()
    {
        return $this->belongsTo('App\Bank', 'bank_id');
    }

    public function collateral()
    {
        return $this->belongsTo('App\User', 'collateral_id');
    }

    public function creditor()
    {
        return $this->hasMany('App\User', 'collateral_id');
    }

    public function files()
    {
        return $this->hasMany('App\File');
    }
    public function propertyFor()
    {
        return $this->belongsTo('App\PropertyFor', 'property_for');
    }
    public function propertyType()
    {
        return $this->belongsTo('App\PropertyFor', 'property_type');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    /**
        Class Methods
    */
    public function getFullnameAttribute($value){
         return $this->firstname.' '.$this->lastname;
     }
    public function getServiceCompany(){
         return $this->companies->where('type',true)->load(["servicesList","files"])->first();
     }
    public function getAgentCompany(){
         return $this->companies->where('type',false)->load(["servicesList","files"])->first();
     }

    public function getConstant(string $constant) {
        return  constant("self::$constant") ;
    }


    public static function generateToken(){
        return str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());
    }

    public function setDefaultMembership($role_id){
        $this->memberships()->attach(Membership::getDefaultMembership($role_id)->id);
        return ;
    }

    public function setDefaultUriRegistrationRole()
    {

        switch ($this->memberships()->first()->role->name) {
            case 'Arrendatario':
                $this->tenant_profile_redirect = self::TENANT_REDIRECT_URI;
                $profile_redirect = $this->tenant_profile_redirect;
                break;
            case 'Propietario':
                $this->owner_profile_redirect = self::OWNER_REDIRECT_URI;
                $profile_redirect = $this->owner_profile_redirect;
                break;
            case 'Agente':
                $this->agent_profile_redirect = self::AGENT_REDIRECT_URI;
                $profile_redirect = $this->agent_profile_redirect;
                break;
            case 'Servicio':
                $this->service_profile_redirect = self::SERVICE_REDIRECT_URI;
                $profile_redirect = $this->service_profile_redirect;
                break;
            default:
                $this->collateral_profile_redirect = self::COLLATERAL_REDIRECT_URI;
                $profile_redirect = $this->collateral_profile_redirect;
                break;
        }
        $this->save();
        return $profile_redirect;
    }

    public function checkEmploymentOwner()
    {
        return $this->employment_type == self::OWNER_EMPLOYMENT_TYPE;
    }

    public function checkEmploymentEmployee()
    {
        return $this->employment_type == self::EMPLOYEE_EMPLOYMENT_TYPE;
    }

    public function checkEmploymentUnemployed()
    {
        return $this->employment_type == self::UNEMPLOYED_EMPLOYMENT_TYPE;
    }
    public function saveBasicData($request){

        $this->country_id = $request->nationality;
        $this->document_type = $request->doc_type;
        $this->document_number = $request->document_number;
        $date = str_replace('/', '-', $request->birthdate);
        $this->birthdate = date('Y-m-d', strtotime($date));
        $this->civil_status_id = $request->civil_status;
        $this->save();
    }

    public function saveLocationData($request){
        $this->city_id = $request->city;
        $this->address = $request->address;
        $this->address_details = $request->address_details;
        $this->latitude = $request->latitude;
        $this->longitude = $request->longitude;
        $this->save();
    }

    public function updateProfileRedirect($profile_redirect, $role)
    {
        switch ($role) {
            case 'Arrendatario':
                $this->tenant_profile_redirect = $profile_redirect;
                break;
            case 'Propietario':
                $this->owner_profile_redirect = $profile_redirect;
                break;
            case 'Agente':
                $this->agent_profile_redirect = $profile_redirect;
                break;
            case 'Servicio':
                $this->service_profile_redirect = $profile_redirect;
                break;
            default:
                $this->collateral_profile_redirect = $profile_redirect;
                break;
        }
        $this->save();
    }
    /**
     * Specifies the user scoring
     */
    public function getScoringAttribute($property_id) {
        if($property_id) {
            return @Routine::calculateUserScoring($this->id, $property_id) ?? 0;
        }
        return 0;
    }

    public function getScoring($property_id) {
        if($property_id) {
            return @Routine::calculateUserScoring($this->id, $property_id) ?? 0;
        }
        return 0;
    }

    public function getTenantMerbership() {
        if(!Membership::checkTenantMemberships()) return 0;
        return $this->memberships()->where('role_id', Membership::TYPE_TENANT)->first();
    }

    public function getInfoTenantMerbership() {
        return $this->memberships()->where('role_id', Membership::TYPE_TENANT)->first();
    }

    public function getOwnerMerbership() {
        if(!Membership::checkOwnerMemberships()) return 0;
        return $this->memberships()->where('role_id', Membership::TYPE_OWNER)->first();
    }

    public function getAgentMerbership() {
        if(!Membership::checkAgentMemberships()) return 0;
        return $this->memberships()->where('role_id', Membership::TYPE_AGENT)->first();
    }
    // Estos metodos son muy similares a los anteriores sin Once pero no consulta con el usuario en Sesión. by AA
    public function getTenantMerbershipOnce() {
        return $this->memberships()->where('role_id', Membership::TYPE_TENANT)->first();
    }

    public function getOwnerMerbershipOnce() {
        return $this->memberships()->where('role_id', Membership::TYPE_OWNER)->first();
    }

    public function getAgentMerbershipOnce() {
        return $this->memberships()->where('role_id', Membership::TYPE_AGENT)->first();
    }

    public function getServiceMerbershipOnce() {
        return $this->memberships()->where('role_id', Membership::TYPE_SERVICE)->first();
    }

    public function generateProfileNotification($role_name){
        switch ($role_name) {
            case 'Arrendatario':
                $this->notify(new TenantProfile($this->fullname));
                if (!is_null($this->collateral)) {
                    $this->collateral->notify(new CollateralMail($this->collateral->activation_token, $this->fullname, $this->id));
                }
                $this->updateProfileRedirect(null,$role_name);
                break;
            case 'Propietario':
                $this->notify(new OwnerProfile($this->fullname));
                $p = $this->getOwnedProperties(true)->first();
                $p->active = true;
                $p->save();
                $this->updateProfileRedirect(null,$role_name);
                break;
            case 'Agente':
                $this->notify(new AgentProfile($this->fullname));
                $this->updateProfileRedirect(null,$role_name);
                break;
            case 'Servicio':
                $this->notify(new ServiceProfile($this->fullname));
                $this->updateProfileRedirect(null,$role_name);
                break;
            default:
                $this->notify(new CollateralProfile($this->fullname));
                $this->updateProfileRedirect(null,$role_name);
                break;
        }
        $this->save();
    }

    public function getUpgradeMembershipUrlAttribute(){
        //$profile = $this->
    }

    public function getOnceRoleAttribute()
    {
        $role = '';
        if ($this->getTenantMerbershipOnce()){
            $role = 'tenant';
            return $role;
        }
        if ($this->getOwnerMerbershipOnce()){
            $role = 'owner';
            return $role;
        }
        if( $this->getAgentMerbershipOnce()){
            $role = 'agent';
            return $role;
        }
        return $role;
    }

    public function contracts()
	{
		return $this->belongsToMany('App\Contract', 'users_has_contracts', 'user_id', 'contract_id')
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
								'signer_role', 
								'ip_address', 
								'auditory_file' )
					->withTimestamps();
	}
    
    public function sasapplicant()
    {
        return $this->hasOne('App\Sasapplicant');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function getCollateralUser()
    {
        if($this->collateral_id)
        {
            return User::find($this->collateral_id);
        } else {
            return null;
        }
    }
    public function routeNotificationForTwilio()
    {
        return '+'.$this->phone_code.$this->phone;
    }

    public function isVerified()
    {
        //Ahora se le permitirá validarse s&s si tiene el carnet trasero no vigente
        return ( $this->files()->where('name', 'id_front')->first()->verified == 1 );
        //return ( $this->files()->where('name', 'id_front')->first()->verified == 1 && $this->files()->where('name', 'id_back')->first()->verified == 1 );
    }
    public function isVerifiedInForce()
    {
        return ( $this->files()->where('name', 'id_front')->first()->verified == 1 && $this->files()->where('name', 'id_back')->first()->verified_ocr == 1 );
    }
    public function isRegisterPreCompleted()
    {
        return (    $this->firstname && 
                    $this->lastname && 
                    $this->country()->first() && 
                    $this->civilStatus()->first() && 
                    $this->document_number &&
                    $this->address &&
                    $this->address_details && 
                    $this->city()->first() && 
                    $this->email );
    }
}
