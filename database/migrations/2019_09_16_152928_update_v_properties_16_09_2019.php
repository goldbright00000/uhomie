<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVProperties16092019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $file = realpath(__DIR__.'/../routines/cambios/16-09-2019/v_properties.sql');
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

            "DROP VIEW IF EXISTS v_properties; "

        ;

        DB::connection()->getPdo()->exec($command);

        
    }
}
