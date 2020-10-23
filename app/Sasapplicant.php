<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sasapplicant extends Model
{
    protected $fillable = [ 
                            
                            'tokenSas',
                            'applicant_id',
                            'inspection_id',
                            'correlation_id',
                            'external_user_id',
                            'type',
                            'review_answer',
                            'review_reject_type',
                            'review_moderation_comment',
                            'review_client_comment' ,
                            'review_status',
                            
                            'user_id'
                          ];



    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
