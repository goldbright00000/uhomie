<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class PropertiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $pets = ['cat', 'dog', 'other', 'no'];
        $property = [];
        for ($i=0; $i < 3; $i++) {
            $property[] = array(
                'name' => 'Propiedad '.($i+1),
                'rent' => random_int(500000, 1000000),
                'description' => $faker->text,
                'property_type_id' => random_int(1, 6),
                'commune_id' => 1,
                'city_id' => 1,
                'pet_preference'=>$pets[random_int(0, 3)],
                'smoking_allowed'=> $faker->boolean(50),
                'is_project'=>$faker->boolean(50),
                'active'=>true,
                'available'=> true,
                'available_date' => Carbon::now()->addDays(random_int(1, 120))->format('Y-m-d'),
                'meters' => random_int(300, 1000),
                'bedrooms' => random_int(3, 9),
                'bathrooms' => random_int(2, 5),

                'private_parking' => random_int(2, 5),
                'public_parking' => random_int(2, 5),
                'verified' => 1,
                'furnished' =>$faker->boolean(10),
                'company_id'=>null,

            );
        }
        $property[] = array(
                'name' => 'Propiedad '.(4),
                'rent' => random_int(500000, 1000000),
                'description' => $faker->text,
                'property_type_id' => random_int(1, 6),
                'commune_id' => 1,
                'city_id' => 1,
                'pet_preference'=>$pets[random_int(0, 3)],
                'smoking_allowed'=> $faker->boolean(50),
                'is_project'=>true,
                'active'=>true,
                'available'=> true,
                'available_date' => Carbon::now()->addDays(random_int(1, 120))->format('Y-m-d'),
                'meters' => random_int(300, 1000),
                'bedrooms' => random_int(3, 9),
                'bathrooms' => random_int(2, 5),

                'private_parking' => random_int(2, 5),
                'public_parking' => random_int(2, 5),
                'verified' => 1,
                'furnished' =>$faker->boolean(10),
                'company_id'=>1,

            );
        
        DB::table('properties')->insert($property);

        // departamento para arrendador prueba
        
        $propiedad_nueva = array(
                'id' => 5,
                'name' => 'Departamento en pleno centro de Santiago',
                'active' => 1,
                'status' => 0,
                'views' => 0,
                'available' => 1,
                'address' => 'Cóndor 1107, Santiago, Chile',
                'address_details' => 'depto 2502',
                'rent' => 320000,
                'description' => 'cercano a linea 1, 2 y 3',
                'is_project' => 0,
                'tenanting_insurance' => 0,
                'condition' => 0,
                'meters' => 70,
                'latitude' => -33.450449,
                'longitude' => -70.65071590000002,
                'warranty_months_quantity' => 1,
                'months_advance_quantity' => 1,
                'available_date' => '2019-06-30',
                'tenanting_months_quantity' => 1,
                'collateral_require' => 0,
                'furnished' => 0,
                'cellar' => 0,
                'visit' => 0,
                'bedrooms' => 2,
                'bathrooms' => 1,
                'pool' => 0,
                'garden' => 0,
                'terrace' => 1,
                'private_parking' => 0,
                'public_parking' => 1,
                'pet_preference' => 'no',
                'smoking_allowed' => 0,
                'property_type_id' => 2,
                'city_id' => 127,
                'verified' => 1,
                'company_id'=>1,

            );
        DB::table('properties')->insert($propiedad_nueva);
        $np = \App\Property::find(5);
        \App\File::generatePropertyFiles($np);
        $property_for = [];
        for ($i=1; $i < 5; $i++) {
            $property_for[] = array(
                'property_id' => $i,
                'property_for_id' => random_int(1, 7)
            );
        }
        DB::table('properties_has_properties_for')->insert($property_for);

        $has_properties = [];
        for ($i=1; $i <= 22; $i++) {
            if ($i==1) {
                $has_properties[] = array(
                                        'user_id' => 7,
                                        'property_id' => 1,
                                        'type' => 1
                                        );
                $has_properties[] = array(
                                        'user_id' => 8,
                                        'property_id' => 2,
                                        'type' => 1
                                        );

                $has_properties[] = array(
                                        'user_id' => 9,
                                        'property_id' => 3,
                                        'type' => 1
                                        );

                $has_properties[] = array(
                                        'user_id' => 4,
                                        'property_id' => 3,
                                        'type' => 3
                                        );

                $has_properties[] = array(
                                        'user_id' => 5,
                                        'property_id' => 3,
                                        'type' => 3
                                        );

                $has_properties[] = array(
                                        'user_id' => 6,
                                        'property_id' => 3,
                                        'type' => 3
                                        );


                }
            else{
                $has_properties[] = array(
                                    'user_id' => random_int(1, 11),
                                    'property_id' => random_int(1, 3),
                                    'type' =>  2);
                }

        }
        // agregando propiedad stgo centro a usuario arrendador prueba
        $has_properties[] = array(
            'user_id' => 13,
            'property_id' => 5,
            'type' => 1
            );

        DB::table('users_has_properties')->insert($has_properties);

        $photos = array(
            array(
                'name' => 'frente1',
                'original_name' => 'frente_completo1',
                'cover' => 1,
                'path' => '/images/explore/img_propiedad.png',
                'user_id' => 7,
                'property_id' => 1,
            ),
            array(
                'name' => 'frente2',
                'original_name' => 'frente_completo2',
                'cover' => 1,
                'path' => '/images/explore/img_propiedad.png',
                'user_id' => 8,
                'property_id' => 2,
            ),
            array(
                'name' => 'frente3',
                'original_name' => 'frente_completo3',
                'cover' => 1,
                'path' => '/images/explore/img_propiedad.png',
                'user_id' => 9,
                'property_id' => 3,
           ),
                array(
                'name' => 'frente4',
                'original_name' => 'frente_completo4',
                'cover' => 1,
                'path' => '/images/explore/img_propiedad.png',
                'user_id' => 1,
                'property_id' => 4,
            ),
            array(
                'name' => 'frente5',
                'original_name' => 'frente_completo4',
                'cover' => 1,
                'path' => '/images/explore/img_propiedad.png',
                'user_id' => 13,
                'property_id' => 5,
            ),
        );
     DB::table('photos')->insert($photos);

    }
}
