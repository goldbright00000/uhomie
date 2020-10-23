<?php

use Illuminate\Database\Seeder;

class ServicesTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $services_type = array(
            array('name' => 'Reparaciones Generales'),
            array('name' => 'Servicios al Hogar'),
            array('name' => 'Profesionales Carrera')
        );
        DB::table('services_type')->insert($services_type);
    }
}
