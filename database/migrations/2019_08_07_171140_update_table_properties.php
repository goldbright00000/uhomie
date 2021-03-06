<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->date('expire_at')->nullable()->after('company_id');
        });

        $command =

            "DROP VIEW IF EXISTS v_properties; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/cambios/08-08-2019/v_properties.sql');
        DB::unprepared( file_get_contents($file) );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('expire_at');
        });

        $command =

            "DROP VIEW IF EXISTS v_properties; "

        ;

        DB::connection()->getPdo()->exec($command);

        $file = realpath(__DIR__.'/../routines/v_properties.sql');
        DB::unprepared( file_get_contents($file) );
    }
}
