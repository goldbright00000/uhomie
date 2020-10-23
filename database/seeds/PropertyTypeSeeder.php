<?php

use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $properties_types = array(
            array(
                'name' => 'Casa',
                'enabled' => true
            ),
            array(
                'name' => 'Departamento',
                'enabled' => true
            ),
            array(
                'name' => 'Habitación',
                'enabled' => true
            ),
            array(
                'name' => 'Local Comercial',
                'enabled' => false
            ),
            array(
                'name' => 'Oficina',
                'enabled' => false
            ),
            array(
                'name' => 'Terreno',
                'enabled' => true
            ),
        );
        DB::table('properties_types')->insert($properties_types);
    }
}
