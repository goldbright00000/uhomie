<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTablePropertiesFor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties_for', function (Blueprint $table) {
            $table->boolean('type')->default(0);
        });
        DB::table('properties_for')->insert([
            ['name' => 'Local de Belleza / Peluquerías', 'type' => 1],
            ['name' => 'Mini-Market', 'type' => 1],
            ['name' => 'Distribuidoras', 'type' => 1],
            ['name' => 'Cafés', 'type' => 1],
            ['name' => 'Oficinas Administrativas', 'type' => 1],
            ['name' => 'Restaurantes', 'type' => 1],
            ['name' => 'Oficina de Ventas', 'type' => 1],
            ['name' => 'Discotecas', 'type' => 1],
            ['name' => 'Ensambladoras', 'type' => 1],
            ['name' => 'Boutiques', 'type' => 1],
            ['name' => 'Locales Comerciales de Todo Tipo', 'type' => 1],
            ['name' => 'Otros', 'type' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties_for', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
