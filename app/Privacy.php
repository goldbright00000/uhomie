<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $table= 'privacies';

    public function users()
    {
        return $this->belongToMany('App\User', 'users_has_privacies', 'privacy_id', 'user_id');
    }

}
