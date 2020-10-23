<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    /**
		Class Constants
	  */
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname','lastname','cell_phone','email','bathrooms','bedrooms','price','furnished_date','commune_id'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [ ];
    /**
    ORM Relationships
    */
    public function commune()
  	{
  		return $this->belongsTo('App\Commune');
  	}
    /**
      Class Methods
    */
}
