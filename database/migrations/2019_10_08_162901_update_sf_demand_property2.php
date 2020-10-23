<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSfDemandProperty2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $command =

            "DROP FUNCTION IF EXISTS sf_demand_property;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/08-10-2019/sf_demand_property.sql');
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

            "DROP FUNCTION IF EXISTS sf_demand_property;"

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/27-08-2019/sf_demand_property.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
