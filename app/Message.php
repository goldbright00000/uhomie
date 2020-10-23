<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $casts = [
    	'written_by_me' => 'boolean'
    ];

	protected $appends = ['from'];

    public function getFromAttribute()
    {
        return $this->from()->first(['photo'])->photo;
    }

    // public function getToAttribute()
    // {
    //     return $this->to()->first(['photo'])->to;
    // }

    public function from() 
    {
    	return $this->belongsTo(User::class, 'from_id');
    }

    // public function to() 
    // {
    // 	return $this->belongsTo(User::class, 'to_id');
    // }
}