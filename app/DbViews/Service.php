<?php

namespace App\DbViews;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
  protected $table = "v_services";

  public function company()
  {
      return $this->belongsTo('App\Company');
  }

  public function user()
  {
      return $this->belongsTo('App\User');
  }

  public function city()
  {
      return $this->belongsTo('App\City');
  }
}
