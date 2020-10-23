<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	/**
		Class Constraints
	*/

	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name', 'hidden'
	];
	/**
		ORM Relationships
	*/

	public function permissions()
	{
		return $this->belongsToMany('App\Permission', 'roles_has_permissions', 'role_id', 'permission_id');
	}

	public function memberships()
	{
		return $this->hasMany('App\Membership');
	}
}
