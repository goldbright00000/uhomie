<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
	protected $appends = ['contact_name', 'contact_photo'];
	protected $fillable = ['user_id', 'contact_id', 'last_message', 'last_time'];

    public function getContactNameAttribute()
    {
    	$name = $this->contact()->first()->firstname . ' ' .
				$this->contact()->first()->lastname;
        if(strlen($name) > 15){
            $name = substr($name, 0, 13).'...';
        }
    	return $name;
    }

    public function getContactPhotoAttribute()
    {
        return $this->contact()->first(['photo'])->photo;
    }

    public function contact() 
    {
        // Conversation N   1 User (contact)
    	return $this->belongsTo(User::class);
    }
}
