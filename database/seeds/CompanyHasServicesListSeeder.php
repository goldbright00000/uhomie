<?php

use Illuminate\Database\Seeder;

class CompanyHasServicesListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       for ($j= 1; $j <= 7; $j++) {
       		
       		for ($i= 1; $i <=3 ; $i++) {
       		
       			$compayHasServices[] = array(
       				'company_id' => $j,
       				'service_list_id'=>random_int(1, 9), 
       				);

       		}
       	}
    	DB::table('companies_has_services_list')->insert($compayHasServices);    
    }

}
