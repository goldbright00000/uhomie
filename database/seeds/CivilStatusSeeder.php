<?php

use Illuminate\Database\Seeder;

class CivilStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $civil_satatus = array(
            array(
                'name' => 'Soltero(a)'
            ),
            array(
                'name' => 'Casado(a)'
            ),
            array(
                'name' => 'Viudo(a)'
            ),
            array(
                'name' => 'Divorciado(a)'
            ),
            array(
                'name' => 'En Convivencia Civil'
            ),
            array(
                'name' => 'Otro'
            ),
        );
        DB::table('civil_status')->insert($civil_satatus);
    }
}
