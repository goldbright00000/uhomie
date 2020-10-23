<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProperty extends Model
{
    public $timestamps    = false;
    
    protected $table      = 'users_has_properties';
    protected $fillable  = ['user_id', 'property_id', 'type', 'espera'];

    const TYPE_OWNER 		= 1;
	const TYPE_APPLICATION 	= 2;
	const TYPE_FAVOURITE 	= 3;
    const TYPE_SCHEDULE 	= 4;
    const TYPE_AGENT     	= 5;        // AGENTE - 04-03-19


    static public function boot() {
        parent::boot();
    }
    
    public function save(array $options = []) {
        return parent::save($options);
    }

    static public function tableName() {
        return (new self)->table;
    }

    static public function attributes() {
        return  (new self)->attributes;
    }

    /**
     * Relations
     */
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function property() {
        return $this->belongsTo('App\Property', 'property_id');
    }
}
