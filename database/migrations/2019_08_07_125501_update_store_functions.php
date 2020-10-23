<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStoreFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP FUNCTION IF EXISTS sf_finantial_user_score; " .
            "DROP FUNCTION IF EXISTS sf_score; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/08-08-2019/sf_finantial_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/cambios/08-08-2019/sf_score.sql');
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

            "DROP FUNCTION IF EXISTS sf_finantial_user_score; " .
            "DROP FUNCTION IF EXISTS sf_score; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/sf_finantial_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_score.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
