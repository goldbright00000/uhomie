<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model {
    /**
     Model config
    */
    protected $table_name = 'contact_messages';

    protected $fillable = ['name', 'email', 'phone', 'reason_contact', 'message'];

    /**
     Model States
     */
    const REASON_PUBLISH        = 0;
    const REASON_KNOW_UHOMIE    = 1;
    const REASON_LEASE          = 2;
    const REASON_CONTACT        = 3;

    
    static public function getReasonsList() {
        return array_keys(self::getReasonsLabels());
    }

    static public function getReasonsLabels() {
        return [
            self::REASON_PUBLISH => "Publicar propiedad", 
            self::REASON_KNOW_UHOMIE => "Conocer mas de uHomie", 
            self::REASON_LEASE => "Arrendar una propiedad", 
            self::REASON_CONTACT => "Contactar con servicio al cliente"
        ];
    }

    
}