<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $fillable = ['name'];

    public function users(){
        return $this->belongsToMany('App\User', 'user_provider', 'provider_id', 'user_id')->withPivot('user_provider_id');
    }
}
