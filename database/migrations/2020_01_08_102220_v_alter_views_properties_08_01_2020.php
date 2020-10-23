<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VAlterViewsProperties08012020 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP FUNCTION IF EXISTS sf_applied; ".
            "DROP FUNCTION IF EXISTS sf_favorite; ".
            "DROP VIEW IF EXISTS v_properties; ". 
            "DROP VIEW IF EXISTS v_properties_wu; ".
            "DROP VIEW IF EXISTS v_agent;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/08-01-2020/sf_applied.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/cambios/08-01-2020/sf_favorite.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/cambios/08-01-2020/v_properties.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/cambios/08-01-2020/v_properties_wu.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/cambios/08-01-2020/v_agent.sql');
        DB::unprepared( file_get_contents($file) );
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
