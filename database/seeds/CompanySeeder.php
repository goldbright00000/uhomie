<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();
    	$companies[] = array(
                'name' => $faker->company(),
                'phone' =>  $faker->phoneNumber(),
                'cell_phone' =>  $faker->phoneNumber(),
                'website' =>  $faker->domainName(),
                'email' =>  $faker->companyEmail(),
                'description' =>  $faker->sentence($nbWords = 6, $variableNbWords = true),
                'type' =>  $faker->boolean(50),
                'invoice' =>  $faker->boolean(50),
                'personal_publish' =>  $faker->boolean(50),
                'personal_address' =>  $faker->boolean(50),
                'sii' =>  $faker->boolean(50),
                'address' =>  $faker->sentence($nbWords = 6, $variableNbWords = true),
                'address_details'=>$faker->sentence($nbWords = 6, $variableNbWords = true),
                'user_id' =>  4,
                'city_id' =>  random_int(1, 6329),
            );
        for ($i= 0; $i < 7; $i++) {
            $companies[] = array(
                'name' => $faker->company(),
                'phone' =>  $faker->phoneNumber(),
                'cell_phone' =>  $faker->phoneNumber(),
                'website' =>  $faker->domainName(),
                'email' =>  $faker->companyEmail(),
                'description' =>  $faker->sentence($nbWords = 6, $variableNbWords = true),
                'type' =>  $faker->boolean(50),
                'invoice' =>  $faker->boolean(50),
                'personal_publish' =>  $faker->boolean(50),
                'personal_address' =>  $faker->boolean(50),
                'sii' =>  $faker->boolean(50),
                'address' =>  $faker->sentence($nbWords = 6, $variableNbWords = true),
                'address_details'=>$faker->sentence($nbWords = 6, $variableNbWords = true),
                'user_id' =>  random_int(5, 10),
                'city_id' =>  random_int(1, 6329),
            );
        }
		DB::table('companies')->insert($companies);  
    }
}
             