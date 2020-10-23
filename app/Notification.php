<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public function users()
    {
        return $this->belongToMany('App\User', 'users_has_notifications', 'notification_id', 'user_id');
    }
}
