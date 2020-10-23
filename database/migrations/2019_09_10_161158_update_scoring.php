<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP FUNCTION IF EXISTS sf_score;".
            "UPDATE det_scoring SET points = 5 WHERE id = 38;".
            "UPDATE det_scoring SET points = 5 WHERE id = 39;".
            "UPDATE det_scoring SET points = 5 WHERE id = 40;".
            "UPDATE det_scoring SET points = 5 WHERE id = 41;".
            "UPDATE det_scoring SET points = 5 WHERE id = 42;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/10-09-2019/sf_score.sql');
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

            "DROP FUNCTION IF EXISTS sf_score;".
            "UPDATE det_scoring SET points = 1 WHERE id = 38;".
            "UPDATE det_scoring SET points = 1 WHERE id = 39;".
            "UPDATE det_scoring SET points = 1 WHERE id = 40;".
            "UPDATE det_scoring SET points = 1 WHERE id = 41;".
            "UPDATE det_scoring SET points = 1 WHERE id = 42;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/08-08-2019/sf_score.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
