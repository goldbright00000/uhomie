 <?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $documents = ['RUT','PASAPORTE','RUT_PROVISIONAL'];
        $pets = ['cat', 'dog', 'other', 'no'];
        $collateral = array(
            array(
                'firstname' => 'Señor',
                'lastname' => 'Aval',
                'email' => 'uno@gmail.com',
                'password' => \Hash::make('0000'),
                'photo' => '/images/avatars/user01.jpg',
                'activation_token' => 'ertoken',
                'mail_verified' => true,
                'phone_verified' => true,
                'created_by_reference' => true,
                'employment_type' => 1,
                'amount' => 900,
                'document_type'=>'PASAPORTE',
                'save_amount'=>12000,
                'other_income_amount' =>700,
                'last_invoice_amount'=> 3000,
                'collateral_id'=>null,
                'confirmed_collateral'=>false,
                'months_advance_quantity'=>1,
                'tenanting_months_quantity'=>8,
                'warranty_months_quantity'=>1,
                'pet_preference'=>'dog',
                'smoking_allowed'=> true,
                'property_type' => 5,
                'move_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'country_id' => 10,
            )
        );
        
        DB::table('users')->insert($collateral);
        $faker = Faker::create();
        $documents = ['RUT','PASAPORTE','RUT_PROVISIONAL'];
        $pets = ['cat', 'dog', 'other', 'no'];
        $collateral = array(
            array(
                'firstname' => 'Jose',
                'lastname' => 'Gutierrez',
                'email' => 'dos@gmail.com',
                'password' => \Hash::make('0000'),
                'photo' => '/images/avatars/user02.jpg',
                'activation_token' => 'ertoken',
                'mail_verified' => true,
                'phone_verified' => false,
                'created_by_reference' => true,
                'employment_type' => 2,
                'amount' => 0,
                'document_type'=>'PASAPORTE',
                'save_amount'=>12000,
                'other_income_amount' =>700,
                'last_invoice_amount'=> 3000,
                'collateral_id'=>1,
                'confirmed_collateral'=>false,
                'months_advance_quantity'=>1,
                'tenanting_months_quantity'=>8,
                'warranty_months_quantity'=>1,
                'pet_preference'=>'dog',
                'smoking_allowed'=> true,
                'property_type' => 5,
                'move_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'country_id' => 25,
                'civil_status_id' => 1,
                'city_id' => 17,
                'bank_id' => 2,
            )
        );
        DB::table('users')->insert($collateral);
        
        $users = [];
        for ($i= 0; $i < 10; $i++) {
            $users[] = array(
                'firstname' => $faker->name(),
                'lastname' => $faker->name(),
                'email' => $faker->email(),
                'password' => \Hash::make('0000'),
                'photo' => '/images/avatars/user03.jpg',
                'activation_token' => 'ertoken',
                'mail_verified' => $faker->boolean(50),
                'phone_verified' => $faker->boolean(50),
                'created_by_reference' => false,
                'employment_type' =>  3,
                'property_for' => random_int(1, 7),
                'property_condition' => random_int(0, 2),
                'expenses_limit' => random_int(0, 1000000),
                'common_expenses_limit' => random_int(0, 1000000),
                'amount' => random_int(0, 1000000),
                'document_type'=>$documents[random_int(0, 2)],
                'save_amount'=>random_int(0, 1000000),
                'other_income_amount' =>random_int(0, 1000000),
                'last_invoice_amount'=> random_int(0, 1000000),
                'collateral_id'=>$faker->boolean(40) ? 1 : null,
                'confirmed_collateral'=>$faker->boolean(50),
                'furnished'=>$faker->boolean(50),
                'months_advance_quantity'=>random_int(1, 3),
                'tenanting_months_quantity'=>random_int(1, 24),
                'warranty_months_quantity'=>random_int(1, 3),
                'pet_preference'=>$pets[random_int(0, 3)],
                'smoking_allowed'=> $faker->boolean(50),
                'property_type' => random_int(1, 6),
                'move_date' => Carbon::now()->addDays(random_int(1, 120))->format('Y-m-d'),
                'country_id' => $i == 0 ? 39 : random_int(1, 50),
            );
        }

        DB::table('users')->insert($users);

        $memberships = [];
        for ($i=2; $i < 12; $i++) {
            $memberships[] = array(
                'user_id' => $i,
                'membership_id' => random_int(1, 3)
            );
        }
        $memberships[] = array(
                'user_id' => 7,
                'membership_id' => 5
            );
        $memberships[] = array(
                'user_id' => 8,
                'membership_id' => 6
            );
        $memberships[] = array(
                'user_id' => 9,
                'membership_id' => 7
            );
        $memberships[] = array(
                'user_id' => 4,
                'membership_id' => 10
            );

         $memberships[] = array(
                'user_id' => 4,
                'membership_id' => 13
            );

        DB::table('users_has_memberships')->insert($memberships);
        
        
        $arrendador_prueba = 
            array(
                'firstname' => 'Jon',
                'lastname' => 'Doe',
                'email' => 'alejandro.arancibia@uhomie.cl',
                'password' => \Hash::make('AAAAAAAA4/'),
                'photo' => '/images/husky.png',
                'birthdate' => '1995-06-04',
                'phone' => '9774314920',
                'phone_code' => '56',
                'authy_id' => '140848496',
                'active' => 1,
                'activation_token' => 'hxv5721per46mjty5d929c0aqz33lkofsn1gc32cu8w1bidc8',
                'mail_verified' => 1,
                'phone_verified' => 1,
                'document_number' => '8.476.690-9',
                'document_type'=>'RUT',
                'address' => 'Condell 114, Limache, Chile',
                'address_details' => 'segundo piso',
                'latitude' => -32.984233,
                'longitude' => -71.2745885,
                'confirmed_action' => 0,
                'expenses_limit' => 320000,
                'common_expenses_limit' => 60000,
                'warranty_months_quantity'=>0,
                'months_advance_quantity'=>0,
                'tenanting_months_quantity'=>0,
                //'move_date' => '2019-07-01',
                //'property_type' => 5,
                //'property_condition' => 2,
                //'property_for' => 1,
                //'pet_preference' => 'no',
                'furnished' => 0,
                'smoking_allowed' => 0,
                'employment_type' => 0,
                //'position' => 'dev',
                //'company' => 'uhomie',
                //'worked_from_date' => '2019-06-30',
                //'job_type' => 'FullTime',
                'amount' => 0,
                'saves' => 0,
                //'save_amount' => 0,
                //'afp' => 0,
                'last_invoice_amount' => 0,
                'other_income_type' => 0,
                //'other_income_amount' => 0,
                'created_by_reference' => 0,
                'confirmed_collateral' => 0,
                'tenanting_insurance' => 0,
                'account_type' => 'Cuenta Corriente',
                'account_number' => '23232323',
                'bank_id' => 1,
                'country_id' => 39,
                'civil_status_id' => 1,
                'city_id' => 57,
                'remember_token' => 'eK5pFDvCbxSYhllvL0QvbQeIC1k0ajoeo3Gbv4HJruQdy2KH0pfUuXzai8Jq'
                
            );
        DB::table('users')->insert($arrendador_prueba);
        

        $alejandro = 
            array(
                'firstname' => 'Alejandro',
                'lastname' => 'Arancibia',
                'email' => 'alexandrox4@gmail.com',
                'password' => \Hash::make('AAAAAAAA4/'),
                'photo' => '/images/husky.png',
                'birthdate' => '1995-06-04',
                'phone' => '9343305980',
                'phone_code' => '56',
                'authy_id' => '146697545',
                'active' => 1,
                'activation_token' => 'ad3te50zw4j7i4k8yg1rs5n88uh12oxqdccef98dapc6lvm1b',
                'mail_verified' => 1,
                'phone_verified' => 1,
                'document_number' => '19.150.812-2',
                'document_type'=>'RUT',
                'address' => 'Condell 114, Limache, Chile',
                'address_details' => 'segundo piso',
                'latitude' => -32.984233,
                'longitude' => -71.2745885,
                'confirmed_action' => 0,
                'expenses_limit' => 320000,
                'common_expenses_limit' => 60000,
                'warranty_months_quantity'=>1,
                'months_advance_quantity'=>1,
                'tenanting_months_quantity'=>8,
                'move_date' => '2019-07-01',
                'property_type' => 5,
                'property_condition' => 2,
                'property_for' => 1,
                'pet_preference' => 'no',
                'furnished' => 0,
                'smoking_allowed' => 0,
                'employment_type' => 1,
                'position' => 'dev',
                'company' => 'uhomie',
                'worked_from_date' => '2019-06-30',
                'job_type' => 'FullTime',
                'amount' => 1000000,
                'saves' => 0,
                'save_amount' => 0,
                'afp' => 0,
                'last_invoice_amount' => 0,
                'other_income_type' => 0,
                'other_income_amount' => 0,
                'created_by_reference' => 0,
                'confirmed_collateral' => 0,
                'tenanting_insurance' => 0,
                'country_id' => 39,
                'civil_status_id' => 1,
                'city_id' => 57,
                'remember_token' => 'eK5pFDvCbxSYhllvL0QvbQeIC1k0ajoeo3Gbv4HJruQdy2KH0pfUuXzai8Jq'
                
            );
        DB::table('users')->insert($alejandro);

        $memberships_new[] = array(
                'user_id' => 13,
                'membership_id' => 5
            );

        $memberships_new[] = array(
                'user_id' => 14,
                'membership_id' => 1
            );

        DB::table('users_has_memberships')->insert($memberships_new);
    }
}
