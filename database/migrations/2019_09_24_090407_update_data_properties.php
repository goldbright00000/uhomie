<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('properties_types')->insert(
            ['name' => 'Bodega', 'enabled' => 0]
        );
        DB::table('spaces')->insert(
            ['name' => 'Planos']
        );
        DB::table('properties_types')->insert(
            ['name' => 'Estacionamiento','enabled' => 0]
        );
        DB::table('properties_types')
            ->where('name', 'Oficina')
            ->update(['enabled' => 1]);
        DB::table('properties_types')
            ->where('name', 'Terreno')
            ->update(['enabled' => 0]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('properties_types')->where('name', '=', 'Estacionamiento')->delete();
        DB::table('spaces')->where('name', '=', 'Planos')->delete();
        DB::table('properties_types')
        ->where('name', 'Oficina')
            ->update(['enabled' => 0]);
            DB::table('properties_types')
        ->where('name', 'Oficina')
            ->update(['enabled' => 0]);
    }
}
