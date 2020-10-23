<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVServices2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP VIEW IF EXISTS v_services; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/03-09-2019/v_services.sql');
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

            "DROP VIEW IF EXISTS v_services; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/08-08-2019/v_services.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
