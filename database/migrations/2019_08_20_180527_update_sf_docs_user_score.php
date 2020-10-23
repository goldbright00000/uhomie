<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSfDocsUserScore extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP FUNCTION IF EXISTS sf_docs_user_score;".
            "UPDATE det_scoring SET points = 100 WHERE id = 22;".
            "UPDATE det_scoring SET points = 100 WHERE id = 29;".
            "UPDATE det_scoring SET points = 100 WHERE id = 35;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/19-08-2019/sf_docs_user_score.sql');
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

            "DROP FUNCTION IF EXISTS sf_docs_user_score; ".
            "UPDATE det_scoring SET points = 0 WHERE id = 22;".
            "UPDATE det_scoring SET points = 0 WHERE id = 29;".
            "UPDATE det_scoring SET points = 0 WHERE id = 35;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/14-08-2019/sf_docs_user_score.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
