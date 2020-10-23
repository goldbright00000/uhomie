<?php

use Illuminate\Database\Seeder;
use App\Provider;


class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
            DB::table('providers')->insert([
                'name' => 'facebook',
            ]);
        
    }
}
