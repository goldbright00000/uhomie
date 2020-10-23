<?php

namespace App;

use App\Traits\isUserPropertyChild;

class ApplyProperty extends UserProperty {
    use isUserPropertyChild;
    
    // STATES 
    const DEFAULT_STATE = 0;    // Cuando el usuario se postula a una propiedad
    const REJECTED_STATE = 1;   // Cuando el propietario rechaza al postulante
    const ACCEPTED_STATE = 2;   // Cuando el propietario acepta al postulante
    const PAID_OUT_STATE = 3;   // Cuando el arrendatario completa el proceso de pago
    const VERIFIED_STATE = 4;   // Cuando se completa la verificacion de identidad
    const SIGNED_STATE = 5;     // Cuando se completa el proceso de firmas de contrato
    const REFUSED_STATE = 6;    // Cuando se cancela el contrato y se inicia el proceso de reembolso de dinero
    
    public $timestamps = true;
    protected $table = 'postulates';
    protected $attributes = ['state' => self::DEFAULT_STATE,  ];
    //protected $fillable = ['id', 'state', 'espera', 'updated_at', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
    ];

    const RELATION_TYPE = parent::TYPE_APPLICATION;

    public function postulant() {
        return $this->user_property->user;
    }

    
}