<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableProperties06112019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function ($table) {
            $table->unsignedTinyInteger('checkout_hour')->default(9)->after('checkin_hour');
            $table->integer('number_furnished')->nullable()->after('furnished');
            $table->integer('double_beds')->nullable()->after('furnished');
            $table->integer('single_beds')->nullable()->after('furnished');
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
