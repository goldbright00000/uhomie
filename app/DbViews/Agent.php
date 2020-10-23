<?php

namespace App\DbViews;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $table = "v_agent";

    public function company()
    {
    	return $this->hasOne('App\Company', 'id', 'company_id');
    }
}