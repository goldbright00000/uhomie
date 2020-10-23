<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(MembershipSeeder::class);
        $this->call(AmenitySeeder::class);

        $this->call(CivilStatusSeeder::class);
        $this->call(PropertyTypeSeeder::class);
        $this->call(PropertyForSeeder::class);

        $this->call(CountrySeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(CitySeeder::class);

        $this->call(CommuneSeeder::class);
        $this->call(ServicesTypeSeeder::class);
        $this->call(ServicesListSeeder::class);

        $this->call(SpaceSeeder::class);
        $this->call(ScoreTablesSeeder::class);
        $this->call(BankSeeder::class);


        $this->call(NotificationsSeeder::class);
        $this->call(PrivaciesSeeder::class);

        // if (env('APP_DEBUG')) {

            $this->call(UserSeeder::class);
            $this->call(CompanySeeder::class);
            $this->call(PropertiesSeeder::class);
            $this->call(FilesSeeder::class);
            $this->call(CompanyHasServicesListSeeder::class);
            $this->call(PaymentsSeeder::class);
            /*----------  Begin Seeders Chat  ----------*/
            $this->call(ConversationsTableSeeder::class);
            $this->call(MessagesTableSeeder::class);
            /*----------   End Seeders Chat   ----------*/

        // }
        $this->call(UseradminSeeder::class);

        $this->call(SasapplicantSeeder::class);

        $this->call(ProviderSeeder::class);
    }
}
