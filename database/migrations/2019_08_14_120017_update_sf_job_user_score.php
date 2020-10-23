<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSfJobUserScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP FUNCTION IF EXISTS sf_docs_user_score; ".
            "DROP VIEW IF EXISTS v_properties_wu; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/14-08-2019/sf_docs_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/cambios/14-08-2019/v_properties_wu.sql');
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

            "DROP FUNCTION IF EXISTS sf_job_user_score; ".
            "DROP VIEW IF EXISTS v_properties_wu; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/sf_job_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_properties_wu.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
