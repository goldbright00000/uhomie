<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends UserProperty {
    use \App\Traits\isUserPropertyChild;

    // Schedule's states
    const EXPIRED = 3;
    const APPROVED = 2;
    const REFUSED = 1;
    const WAITING_FOR_ACCEPTANCE = 0;

    const RELATION_TYPE = parent::TYPE_SCHEDULE; 

    public $timestamps      = false;
    protected $table        = 'schedules';
    protected $fillable     = ['property_id', 'schedule_date', 'schedule_range'];
    protected $attributes   = [ 'schedule_state' => self::WAITING_FOR_ACCEPTANCE ];

    /**
     Relations
     */
    public function visitor() {
        return parent::user();
    }

}