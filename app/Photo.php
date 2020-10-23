<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $fillable = ['name','original_name','cover','logo','path','user_id','property_id','company_id','service_list_id','space_id'];

    const USERS_URI = '/public/users/';
    const PROPERTIES_URI = '/public/properties/';
    const SERVICES_URI = '/public/services/';
    public $timestamps = false;


    public function getPublicPath(){
        return '/storage/'.$this->path;
    }
    public function getPublicUrl(){
        return env('APP_URL').$this->getPublicPath();
    }

    public function company()
  	{
  		return $this->belongsTo('App\Company');
    }
      
    public function space() {
        return $this->belongsTo('App\Space');
    }

    /**
        Parent Override Methods
    */
    public function delete(){
        try {
            if ($this->user_id) {
                Storage::disk('local')->delete(self::USERS_URI.$this->user_id.'/photos/'.$this->name);
            }elseif ($this->property_id) {
                Storage::disk('local')->delete(self::PROPERTIES_URI.$this->property_id.'/photos/'.$this->name);
            }
            elseif ($this->company_id) {
                Storage::disk('local')->delete(self::SERVICES_URI.$this->company_id.'/photos/'.$this->name);
            }
        } catch (\Exception $e) {

        }
        parent::delete();
    }
}
