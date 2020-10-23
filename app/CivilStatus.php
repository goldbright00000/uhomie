<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CivilStatus extends Model
{
	/**
		Class Table
	*/
	protected $table = 'civil_status';

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

	public function users()
	{
		return $this->hasMany('App\User');
	}

	public function collaterals()
	{
		return $this->hasMany('App\Collateral');
	}
}
