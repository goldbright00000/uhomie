<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCoupons22112019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->integer('membership_id')->unsigned();
            $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade')->onUpdate('cascade');
            $table->string('code')->nullable();
            $table->boolean('enabled')->nullable();
            $table->date('expires_at')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamps();
        });

        Schema::create('users_has_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('coupon_id')->unsigned();
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
