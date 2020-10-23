<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\IsProjectScope;

class Project extends Model
{
	/**
		Class Constraints
	*/
	static $_CONDITION_LABEL = [
		0 => 'En obra',
		1 => 'Terminado',
		2 => 'Otro'
	];

	//Owner
	const TYPE_OWNER 		= 1;

	/**
		Fillables and Model Config
	*/
	protected $table = 'properties';

	protected $fillable = [
		'name'
	];

	protected $appends = ['favourite', 'slug'];

	protected static function boot() {
		parent::boot();

		static::addGlobalScope(new IsProjectScope);
	}

	/**
		ORM Relationships
	*/

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	//Add for backend admin
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

	public function company()
	{
		return $this->belongsTo('App\Company');
	}

	public function amenities()
	{
		return $this->belongsToMany('App\Amenity', 'properties_has_amenities', 'property_id', 'amenity_id');
	}

	/**
	 * Attributes
	 */
	public function getConditionLabelAttribute() {
		return static::$_CONDITION_LABEL[(int) $this->condition];
	}

	/**
	 * Methods
	 */
	/**
	* Return Project's Agent.
	*
	* @return objectArray
	* @return queryBuilder
	*/
	public function getAgent($getQb = false){
		return $this->company()->first()->user()->first();
	}

	public function favourites() {
		return $this->hasMany('App\Favourite');
	}

	public function getSlugAttribute() {
		return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $this->name)));
	}

	 public function getFavouriteAttribute() {
		if(!($user = \Auth::user())) return false;

		return $this->favourites()->where('user_id', $user->id)->count() > 0;
	}

	public function getAppliedAttribute() {
		if(!($user = \Auth::user())) return false;

		return $this->applications()->where('user_id', $user->id)->count() > 0;
	}
}
