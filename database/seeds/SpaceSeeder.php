<?php

use Illuminate\Database\Seeder;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spaces = array(
            array("name" => "Living"),
            array("name" => "Living / Comedor"),
            array("name" => "Comedor"),
            array("name" => "Cocina"),
            array("name" => "Baño Principal"),
            array("name" => "Baño Secundario"),
            array("name" => "Baño Visita"),
            array("name" => "Habitación Principal"),
            array("name" => "Habitación"),
            array("name" => "Terraza"),
            array("name" => "Jardin"),
            array("name" => "Logia / Zona Lavado"),
            array("name" => "Quincho"),
            array("name" => "Habitación Servicio"),
            array("name" => "Sala Estudio"),
            array("name" => "Patio Trasero"),
            array("name" => "Patio Delantero"),
            array("name" => "Zona de Lavado"),
            array("name" => "Planos"),
            array("name" => "Otro")
        );
        DB::table('spaces')->insert($spaces);
    }
}
