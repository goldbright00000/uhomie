<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVAgents2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP VIEW IF EXISTS v_agent; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/22-08-2019/v_agent.sql');
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

            "DROP VIEW IF EXISTS v_agents; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/13-08-2019/v_agent.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
