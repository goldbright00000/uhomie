<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Useradmin extends Authenticatable
{
   use Notifiable;


  const ROLE_ADMIN = 1; // Role admin
  const ROLE_MANAGEMENT = 2; // Role management
  const ROLE_OTHER = 3; // next

	/**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password',
  ];
	/**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

  public function isAdmin()
  {
    //Quedo del primer atributo, solo chequea que tenga role
    return $this->role;
  }
}
