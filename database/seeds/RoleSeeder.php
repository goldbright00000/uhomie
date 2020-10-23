<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            array(
                'name' => 'Arrendatario',
                'hidden' => false,
                'slug' => 'tenants'
            ),
            array(
                'name' => 'Propietario',
                'hidden' => false,
                'slug' => 'owners'
            ),
            array(
                'name' => 'Agente',
                'hidden' => false,
                'slug' => 'agents'
            ),
            array(
                'name' => 'Servicio',
                'hidden' => false,
                'slug' => 'services'
            ),
            array(
                'name' => 'Aval',
                'hidden' => true,
                'slug' => 'collaterals'

            ),
        );
        DB::table('roles')->insert($roles);
    }
}
