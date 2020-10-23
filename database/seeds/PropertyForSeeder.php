<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PropertyForSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties_for = array(
            array(
                'name' => 'Soltero'
            ),
            array(
                'name' => 'Estudiante'
            ),
            array(
                'name' => 'Familia Sin Hijos'
            ),
            array(
                'name' => 'Familia Con Hijos'
            ),
            array(
                'name' => 'Familia Con Mascotas'
            ),
            array(
                'name' => 'Ejecutivos'
            ),
            array(
                'name' => 'Grupos +5'
            ),
        );
        DB::table('properties_for')->insert($properties_for);
    }
}
