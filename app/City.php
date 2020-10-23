<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
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
	public function region()
	{
		return $this->belongsTo('App\Region');
	}
	public function communes()
	{
		return $this->hasMany('App\Commune');
	}
	public function properties()
	{
		return $this->hasMany('App\Property');
	}
	public function users()
	{
		return $this->hasMany('App\Property');
	}
	public function companies()
	{
		return $this->hasMany('App\Company');
	}
}
