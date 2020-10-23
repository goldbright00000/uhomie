<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VProperties10102019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP VIEW IF EXISTS v_properties; ". 
            "DROP VIEW IF EXISTS v_properties_wu; "

        ;

        DB::connection()->getPdo()->exec($command);
        $file = realpath(__DIR__.'/../routines/cambios/09-10-2019/v_properties.sql');
        DB::unprepared( file_get_contents($file) );
        $file = realpath(__DIR__.'/../routines/cambios/09-10-2019/v_properties_wu.sql');
        DB::unprepared( file_get_contents($file) );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $command =

            "DROP VIEW IF EXISTS v_properties; ". 
            "DROP VIEW IF EXISTS v_properties_wu; "

        ;

        DB::connection()->getPdo()->exec($command);
        $file = realpath(__DIR__.'/../routines/cambios/09-10-2019/v_properties.sql');
        DB::unprepared( file_get_contents($file) );
        $file = realpath(__DIR__.'/../routines/cambios/09-10-2019/v_properties_wu.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
