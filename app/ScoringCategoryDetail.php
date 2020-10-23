<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoringCategoryDetail extends Model
{
    //
    protected $table = 'det_scoring';
    public $timestamps = false;
    /**
  		Fillables and Model Config
  	*/
  	protected $fillable = [
  		'cat_scoring_id','name','description','feed_back','points','method'
  	];
  	/**
  		ORM Relationships
  	*/
    public function category()
  	{
  		return $this->belongsTo('App\ScoringCategory','cat_scoring_id');
  	}
}
