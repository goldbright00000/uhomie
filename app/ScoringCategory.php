<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScoringCategory extends Model
{
    //
    protected $table = 'cat_scoring';
    public $timestamps = false;
    /**
  		Fillables and Model Config
  	*/
  	protected $fillable = [
  		'scoring_id', 'name', 'description', 'feed_back'
  	];
  	/**
  		ORM Relationships
  	*/
    public function details()
  	{
  		return $this->hasMany('App\ScoringCategoryDetail','cat_scoring_id');
  	}
    public function scoring()
  	{
  		return $this->belongsTo('App\Scoring','scoring_id');
  	}

}
