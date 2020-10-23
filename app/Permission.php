<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	/**
		Class Constraints
	*/

	/**
		Fillables and Model Config
	*/
	protected $fillable = [
		'name', 'slug'
	];
	/**
		ORM Relationships
	*/

	public function roles()
	{
		return $this->belongsToMany('App\Role', 'roles_has_permissions', 'permission_id', 'role_id');
	}
}
