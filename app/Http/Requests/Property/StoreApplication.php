<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Membership;

class StoreApplication extends FormRequest {

    public function authorize() {
        return $this->user() && Membership::checkTenantMemberships();
    }
    
    public function withValidator($validator) {

        $validator->after(function($validator){

            if(\App\Property::where('id',$this->route('property_id'))->first()->type_stay == 'LONG_STAY'){
                if($this->hasAlreadyApplied()) {
                    $validator->errors()->add('user_id', 'Ya te has postulado o esta postulación está a espera que verifiques tu identidad');
                    $validator->errors()->add('verification_link', '/users/profile/'.\Auth::user()->once_role.'#/configs');
                    return false;
                }
                return false;
            }

            if(!$this->hasApplicationsLeft()) {
                $validator->errors()->add('user_id', trans('validation.postulate_not_left'));
                $validator->errors()->add('upgrade_link', '/users/'.\Auth::user()->once_role.'/memberships-upgrade');
                return false;
            }

            if( \Auth::user()->id == \App\Property::find($this->route('property_id'))->getOwner()->id ){
                $validator->errors()->add('user_id', 'No puedes postular a tu propia propiedad');
                return false;
            }
            /*
            if(!$this->hasBeenVerifiedSumAndSus()){
                $validator->errors()->add('verification', 'Debe validar su identidad para continuar con la postulacion');
                return false;
            }
            */
        });
    }
// por pagar / por validar / por firmar
    public function all($keys = null) {

        $data = parent::all($keys);
        $data['property_id'] = @$this->route('property_id');

        return $data;
    }
    
    /** RULES  */
    public function rules() {
        return [
            'property_id' => [
                'required',
                Rule::exists('properties', 'id')
            ]
        ];
    }

    // Custom Rules
    private function hasApplicationsLeft() {
        return $this->user()->applications_left > 0 || $this->user()->applications_left == -1;
    }
    
    private function hasAlreadyApplied() {
        return $this->user()->applications()->where('property_id', $this->route('property_id'))->count() > 0;
    }

    private function hasBeenVerifiedSumAndSus() {
        // codificar el comprobar que esté verificado su identidad
        return true;
    }
    // Custom Methods
}