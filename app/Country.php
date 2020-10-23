<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
	/**
		Class Constraints
	*/

	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name', 'valid', 'nationality', 'code'
	];
	/**
		ORM Relationships
	*/
	public function cities()
	{
		return $this->hasManyThrough('App\City', 'App\Region');
	}

	public function regions()
	{
		return $this->hasMany('App\Region');
	}

	public function users()
	{
		return $this->hasMany('App\User');
	}
}
