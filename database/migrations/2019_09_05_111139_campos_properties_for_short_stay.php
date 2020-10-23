<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CamposPropertiesForShortStay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function ($table) {
            $table->boolean('special_sale')->default(false);
            $table->unsignedTinyInteger('week_sale')->default(0);
            $table->unsignedTinyInteger('month_sale')->default(0);
            $table->unsignedTinyInteger('minimum_nights')->default(1);
            $table->unsignedTinyInteger('checkin_hour')->default(9);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('special_sale');
            $table->dropColumn('week_sale');
            $table->dropColumn('month_sale');
            $table->dropColumn('minimum_nights');
            $table->dropColumn('checkin_hour');
        });
    }
}
