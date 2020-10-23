<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    //
    protected $fillable = [ 'name'];
    public function users()
    {
    	return $this->hasMany('App\Property');
    }
}
