<?php

use Illuminate\Database\Seeder;

class UseradminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	DB::table('useradmins')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin1234'),
        ]);

        DB::table('useradmins')->insert([
            'name' => 'admin uhomie',
            'email' => 'admin@uhomie.cl',
            'password' => bcrypt('admin1234'),
        ]);
    }
}
