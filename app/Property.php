<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
	//use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
	//protected $dates = ['deleted_at'];
	
	/**
		Class Traits
	*/
    use SoftDeletes;
	/**
	Class Constraints
	*/
	const PROPERTY_CONDITION_ARRAY = array("Nuevo","Usado");

	const TYPE_OWNER 		= 1;
	const TYPE_APPLICATION 	= 2;
	const TYPE_FAVOURITE 	= 3;
	const TYPE_SCHEDULE 	= 4;
	const TYPE_AGENT	 	= 5;

	/**
	Fillables and Model Config
	*/
	protected $fillable = [
		'name', 'status', 'address', 'rent', 'description', 'metas', 'images', 'common_expenses', 'common_expenses_support', 'is_project','common_expenses_limit',
		'invoices', 'property_certificate', 'condition', 'meters', 'rooms_count', 'bathrooms_count', 'latitude', 'longitude',
		'expenses_limit','common_expenses','warranty_months_quantity','months_advance_quantity','available_date','tenanting_months_quantity','collateral_require','furnished','furnished_description','schedule_range','visit','visit_from_date','
		visit_to_date','bedrooms','bathrooms','pool','garden','terrace','private_parking','public_parking','pet_preference','smoking_allowed','nationals_with_rut','foreigners_with_rut' ,'foreigners_with_passport','tenanting_insurance', 'anexo_conditions',
		'type_stay', 'special_sale', 'week_sale', 'month_sale', 'minimum_nights', 'checkin_hour', 'allow_small_child', 'allow_baby', 'allow_parties', 'use_stairs', 'there_could_be_noise', 'common_zones', 'services_limited', 'survellaince_camera', 'weaponry', 'dangerous_animals', 'pets_friendly',
		'exclusions','meeting_room','rooms','level','building_name','work_electric_water','arquitecture_project','civil_work','room_enablement','term_year','rent_year_1','rent_year_2','rent_year_3','penalty_fees','warranty_ticket','warranty_ticket_price',
		'cleaning_rate',
	];
	/**
   * For SoftDeletes.
   *
   */
  protected $dates = ['deleted_at'];

  protected $appends = ['demand', 'favourite', 'applied', 'slug'];

	/**
	ORM Relationships
	*/

	public function propertyType()
	{
		return $this->belongsTo('App\PropertyType');
	}

	public function users()
	{
		return $this->belongsToMany('App\User', 'users_has_properties', 'property_id', 'user_id')->withPivot('type');
	}

	/**
	* Return Property's Owner.
	*
	* @return objectArray
	* @return queryBuilder
	*/
	public function getOwner($getQb = false){
		if(!$getQb)
			return $this->users()->where('type', self::TYPE_OWNER)->first();
		return $this->properties()->where('type', self::TYPE_OWNER);
	}

	public function getAgent($getQb = false){
		if(!$getQb)
			return $this->users()->where('type', self::TYPE_AGENT)->first();
		return $this->properties()->where('type', self::TYPE_AGENT);
	}

	/**
	* Return Property's applied users.
	*
	* @return objectArray
	*/
	public function getAppliedUsers(){
		return $this->users()
			->where('type',
				self::TYPE_APPLICATION)
			->orderBy('id', 'ASC')->get();
	}

	public function amenities()
	{
		return $this->belongsToMany('App\Amenity', 'properties_has_amenities', 'property_id', 'amenity_id');
	}

	public function propertiesFor()
	{
		return $this->belongsToMany('App\PropertyFor', 'properties_has_properties_for', 'property_id', 'property_for_id');
	}

	public function city()
	{
		return $this->belongsTo('App\City', 'city_id', 'id');
	}

	public function files()
	{
		return $this->hasMany('App\File');
	}

	public function photos()
	{
		return $this->hasMany('App\Photo');
	}

	public function favourites() {
		return $this->hasMany('App\Favourite');
	}

	public function applications() {
		return $this->hasManyThrough('App\ApplyProperty', 'App\UserProperty', null, 'id')->withoutGlobalScopes([\App\Scopes\PropertyUserScope::class]);
	}

	/**
	 * Specifies if the property has a high demand
	 */
	public function getDemandAttribute(){
		return Routine::calculatePropertyDemand($this->id)->demand ?? false;
	}

	/**
	 * Specifies if the property has a high score
	 */
	public function getScoringAttributeProp($user_id, $property_id) {
        if($property_id) {
            return @Routine::calculateUserScoring($user_id, $property_id) ?? 0;
        }
        return 0;
    }

	/**
	 * Specifies if the property is User's favourite
	 */
	public function getFavouriteAttribute() {
		if(!($user = \Auth::user())) return false;

		return $this->favourites()->where('user_id', $user->id)->count() > 0;
	}

	/**
	 * Specifies if the property has a current (session) user's application
	 */
	public function getAppliedAttribute() {
		if(!($user = \Auth::user())) return false;

		return $this->applications()->where('user_id', $user->id)->count() > 0;
	}

	/**
	 *
	 */
	public function getSlugAttribute() {
	   return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $this->name)));
	}




	public function contract()
	{
		return $this->hasOne('App\Contract');
	}

}
