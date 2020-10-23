<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyFor extends Model
{
    protected $table = "properties_for";
	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name'
	];
	/**
		ORM Relationships
	*/

	public function propertiesFor()
	{
	  return $this->belongsToMany('App\Property', 'properties_has_properties_for', 'property_for_id', 'property_id');
	}
}
