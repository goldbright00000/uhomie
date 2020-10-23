<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Membership;

class StoreSchedule extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Falta validar que el usuario es de tipo -arrendatario-
        return $this->user();
    }

    public function withValidator($validator) {
        $validator->after(function($validator){
            if(!Membership::checkTenantMemberships()) {
                $validator->errors()->add('property_id', trans('validation.schedule_not_tenant'));
                return false;
            }

            /*if(!$this->scheduleDateBetweenPropertyScheduleDate()){
                $validator->errors()->add('schedule_date', trans('validation.schedule_out_range'));
            }
            
            if(!$this->scheduleHourInPropertyRange()) {
                $validator->errors()->add('schedule_range', trans('validation.schedule_invalid_range'));
            }*/

            if(!$this->scheduleDateRange()){
                $validator->errors()->add('schedule_date', trans('validation.schedule_out_range'));
                $validator->errors()->add('schedule_range', trans('validation.schedule_invalid_range'));
            }

            if($this->hasActiveSchedule()) {
                $validator->errors()->add('property_id', 
                trans('validation.schedule_already_active'));
            }

            if($this->hasPendingSchedule()) {
                $validator->errors()->add('property_id', 
                trans('validation.schedule_pending'));
            }
            
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'property_id' => [
                'required',
                Rule::exists('properties', 'id')->where(function($query){
                    $query->where('visit', 1);
                })
            ],
            'schedule_date' => 'required|date|after:today',
            'schedule_range' => [
                'required',
                'string',
                'regex:/(9-12|12-3|3-7)$/i'
            ]
        ];
    }

    /**
     * Validates if the schedule_date its between visit_date of the property.
     */
    private function scheduleDateBetweenPropertyScheduleDate() {
        $property = \App\Property::find($this->request->get('property_id'));
        
        if(!isset($property)) return false;

        $from = strtotime($property->visit_from_date);
        $to   = strtotime($property->visit_to_date);
        $schedule_date = strtotime($this->request->get('schedule_date'));

        return ($from !== false  && $to !== false) && ($from <= $schedule_date && $schedule_date <= $to);
    }

    private function scheduleHourInPropertyRange() {
        $property = \App\Property::find($this->request->get('property_id'));
        if(!isset($property)) return false;
        
        $available_ranges = explode(' ', $property->schedule_range);

        return count($available_ranges) > 0 && array_search($this->request->get('schedule_range'), $available_ranges) !== false;
    }
    private function hasActiveSchedule() {
        $schedule = \App\Schedule::where('user_id', '=' , $this->user()->id)
            ->where('property_id', '=', $this->request->get('property_id'))
            ->where('schedule_date', '<=', 'NOW()')
            ->where('schedule_state', '=', \App\Schedule::APPROVED)
            ->count();

        return $schedule > 0;
    }

    private function hasPendingSchedule() {
        $schedule = \App\Schedule::where('user_id', '=' , $this->user()->id)
            ->where('property_id', '=', $this->request->get('property_id'))
            ->where('schedule_date', '<=', 'NOW()')
            ->where('schedule_state', '=', \App\Schedule::WAITING_FOR_ACCEPTANCE)
            ->count();

        return $schedule > 0;
    }
    private function scheduleDateRange(){
        $schedules = \App\Property::select('schedule_dates')->where('id', $this->request->get('property_id'))->first();
        $dates = json_decode($schedules->schedule_dates);

        switch($this->request->get('schedule_range')){
            case '9-12':
                foreach ($dates->morning as $morning) {
                    if($morning == $this->request->get('schedule_date')){
                        return true;
                    }
                }
                return false;
                break;

            case '12-3':
                foreach ($dates->noon as $noon) {
                    if($noon == $this->request->get('schedule_date')){
                        return true;
                    }
                }
                return false;
                break;

            case '3-7':
                foreach ($dates->afternoon as $afternoon) {
                    if($afternoon == $this->request->get('schedule_date')){
                        return true;
                    }
                }
                return false;
                break;
            default:
                return false;
                break;

        }
    }
}
