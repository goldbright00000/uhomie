<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    /**
  		Class Constraints
  	*/
  	protected $table = "services_type";
  	/**
  		Fillables and Model Config
  	*/
  	protected $fillable = [
  		'name'
  	];
  	/**
  		ORM Relationships
  	*/
  	public function servicesList()
  	{
  		return $this->hasMany('App\ServiceList');
  	}
}
