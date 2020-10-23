<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
	/**
		Class Constraints
	*/

	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name'
	];
	/**
		ORM Relationships
	*/

	public function country()
	{
		return $this->belongsTo('App\Country');
	}

	public function cities()
	{
		return $this->hasMany('App\City');
	}
}
