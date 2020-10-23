<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
	/**
		Class Constraints
	*/
	protected $table = "properties_types";
	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name'
	];
	/**
		ORM Relationships
	*/

	public function properties()
	{
		return $this->hasMany('App\Property');
	}
}
