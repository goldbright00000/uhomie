<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFieldsPropertyShortStay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function ($table) {
            $table->boolean('allow_small_child')->default(false);
            $table->boolean('allow_baby')->default(false);
            $table->boolean('allow_parties')->default(false);
            $table->boolean('use_stairs')->default(false);
            $table->boolean('there_could_be_noise')->default(false);
            $table->boolean('common_zones')->default(false);
            $table->boolean('services_limited')->default(false);
            $table->boolean('survellaince_camera')->default(false);
            $table->boolean('weaponry')->default(false);
            $table->boolean('dangerous_animals')->default(false);
            $table->boolean('pets_friendly')->default(false);
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
            $table->dropColumn('allow_small_child');
            $table->dropColumn('allow_baby');
            $table->dropColumn('allow_parties');
            $table->dropColumn('use_stairs');
            $table->dropColumn('there_could_be_noise');
            $table->dropColumn('common_zones');
            $table->dropColumn('services_limited');
            $table->dropColumn('survellaince_camera');
            $table->dropColumn('weaponry');
            $table->dropColumn('dangerous_animals');
            $table->dropColumn('pets_friendly');
        });
    }
}
