<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\{Membership, Role, Coupon, UserCoupon, User};
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index(){

    }
    public function getCouponsList(){
        $coupons = Coupon::all();

        foreach ($coupons as $coupon) {
            $membership = Membership::find($coupon->membership_id);
            $role = Role::find($membership->role_id);
            $list[] = [
                'coupon' => $coupon,
                'membership' => $membership,
                'role' => $role
            ];
        }

        return $list;
    }

    public function getCoupon(Request $request){
        $coupon = Coupon::find($request->id);

        $membership = Membership::find($coupon->membership_id);;
        $role = Role::find($membership->role_id);

        $coupon->membership = $membership->name;
        $coupon->role = $role->slug;

        return $coupon;
    }

    public function getListUsersCoupon(Request $request){
        $users_coupon = UserCoupon::where('coupon_id', $request->id)->get();
        
        foreach ($users_coupon as $user_coupon) {
            $user = User::find($user_coupon->user_id);
            $list[] = [
                'id' => $user->id,
                'name' => $user->firstname . ' ' . $user->lastname,
                'phone' => '+'.$user->phone_code . '-' . $user->phone,
                'email' => $user->email,
                'created_at' => $user_coupon->created_at
            ];
        }
        return $list;
    }

    public function CouponCreate(Request $request){
        $role = Role::where('slug', $request->role)->first();
        $membership = Membership::where('role_id', $role->id)->where('name', $request->membership)->first();

        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->membership_id = $membership->id;
        $coupon->code = $request->code;
        $coupon->enabled = $request->enabled;
        $coupon->quantity = $request->quantity;

        if($coupon->save()){
            return response([
                'success' => true
            ], 200);
        } else{
            return response([
                'success' => false
            ], 500);
        }
    }

    public function CouponUpdate(Request $request){
        $role = Role::where('slug', $request->role)->first();
        $membership = Membership::where('role_id', $role->id)->where('name', $request->membership)->first();

        $coupon = Coupon::find($request->id);
        $coupon->name = $request->name;
        $coupon->membership_id = $membership->id;
        $coupon->code = $request->code;
        $coupon->enabled = $request->enabled;
        $coupon->quantity = $request->quantity;

        if($coupon->save()){
            return response([
                'success' => true
            ], 200);
        } else{
            return response([
                'success' => false
            ], 500);
        }
    }
}