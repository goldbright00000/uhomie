<?php

use Illuminate\Database\Seeder;
use App\{File, User};
use Faker\Factory as Faker;

class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker::create();
        $common_files = array(
            array(
                'name' => "id_front",
                'verified' => true,
                'user_id' => 1,
                'val_date' => true,
                'factor' => 0,
            ),
            array(
                'name' => "id_back",
                'verified' => true,
                'user_id' => 1,
                'val_date' => true,
                'factor' => 0,
            ),
            array(
                'name' => "afp",
                'verified' => true,
                'user_id' => 1,
                'val_date' => true,
                'factor' => 0,
            ),
            array(
                'name' => "dicom",
                'verified' => true,
                'user_id' => 1,
                'val_date' => false,
                'factor' => 1000,
            ),

        );
        DB::table('files')->insert($common_files);

    	for ($i=2; $i < 12; $i++) {
    		$common_files = array(
	 			array(
	                'name' => "id_front",
                    'verified' => $faker->boolean(50),
	                'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 0,
	            ),
	            array(
	                'name' => "id_back",
                    'verified' => $faker->boolean(50),
	                'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 0,
	            ),
                array(
                    'name' => "afp",
                    'verified' => $faker->boolean(50),
                    'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 0,
                ),
                array(
                    'name' => "dicom",
                    'verified' => $faker->boolean(50),
                    'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 1000,
                ),
	        );
       		DB::table('files')->insert($common_files);
        }
		for ($i=13; $i <= 14; $i++) {
    		$common_files = array(
	 			array(
	                'name' => "id_front",
                    'verified' => 0,
	                'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 0,
	            ),
	            array(
	                'name' => "id_back",
                    'verified' => 0,
	                'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 0,
	            ),
                array(
                    'name' => "afp",
                    'verified' => 0,
                    'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 0,
                ),
                array(
                    'name' => "dicom",
                    'verified' => 0,
                    'user_id' => $i,
                    'val_date' => $faker->boolean(50),
                    'factor' => 1000,
                ),
	        );
       		DB::table('files')->insert($common_files);
        }

    	$e_users = User::where('employment_type', 1)->get();
    	foreach ($e_users as $user) {
    		$files = array(
	            array(
	                'name' => "first_settlement",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,

	            ),
	            array(
	                'name' => "second_settlement",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,

	            ),
	            array(
	                'name' => "third_settlement",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,

	            ),
	            array(
	                'name' => "work_constancy",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,
	            )
	        );
	        DB::table('files')->insert($files);
    	}
    	$f_users = User::where('employment_type', 2)->get();
    	foreach ($f_users as $user) {
    		$files = array(
	            array(
	                'name' => "other_income",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,
	            ),
	            array(
	                'name' => "last_invoice",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,
	            ),
	            array(
	                'name' => "saves",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,
	            )
	        );
	        DB::table('files')->insert($files);
    	}
    	$u_users = User::where('employment_type', 3)->get();
    	foreach ($u_users as $user) {
    		$files = array(
	            array(
	                'name' => "other_income",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,
	            ),
	            array(
	                'name' => "saves",
	                'verified' => $faker->boolean(50),
                    'val_date' => $faker->boolean(50),
	                'user_id' => $user->id,
	            )
	        );
	        DB::table('files')->insert($files);
    	}
    }
}
