<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    //
    protected $table = 'users_has_coupons';
    protected $fillable  = ['user_id', 'coupon_id'];

    static public function boot() {
        parent::boot();
    }
    
    public function save(array $options = []) {
        return parent::save($options);
    }

    static public function tableName() {
        return (new self)->table;
    }

    static public function attributes() {
        return  (new self)->attributes;
    }

    /**
     * Relations
     */
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function coupon() {
        return $this->belongsTo('App\Coupon', 'coupon_id');
    }
}
