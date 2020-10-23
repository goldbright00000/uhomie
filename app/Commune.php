<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
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

	public function city()
	{
		return $this->belongsTo('App\City');
	}

	public function users()
	{
		return $this->hasMany('App\User');
	}
}
