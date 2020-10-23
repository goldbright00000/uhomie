<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Membership;

class ToggleFavourite extends FormRequest {

    public function authorize() {
        return $this->user() && Membership::checkTenantMemberships();
    }
    
    public function withValidator($validator) {

        $validator->after(function($validator){
        });
    }

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

    // Custom Methods
}