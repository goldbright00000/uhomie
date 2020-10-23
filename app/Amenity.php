<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
	/**
		Class Constraints
	*/

	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name', 'image', 'type' /*type-property: false | type-common: true*/
	];
    /**
		ORM Relationships
	*/

	public function users()
	{
		return $this->belongToMany('App\User', 'users_has_amenities', 'amenity_id', 'user_id');
	}

	public function properties()
	{
		return $this->belongToMany('App\Property', 'properties_has_amenities', 'amenity_id', 'property_id');
	}
}
