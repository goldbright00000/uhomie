<?php

use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $banks = array(
        	array( 'name' => 'Banco de Chile' ),  
        	array( 'name' => 'Banco Internacional' ),  
        	array( 'name' => 'Scotiabank Chile' ),  
        	array( 'name' => 'Banco Crédito e Inversiones' ),  
        	array( 'name' => 'Banco Bice' ),  
        	array( 'name' => 'HSBC Bank ' ),  
        	array( 'name' => 'Banco Santander' ),  
        	array( 'name' => 'Banco Santander Banefe' ),  
        	array( 'name' => 'Banco del Desarrollo' ),  
        	array( 'name' => 'Corpbanca' ),  
        	array( 'name' => 'Banco Security' ),  
        	array( 'name' => 'Banco Falabella' ),  
        	array( 'name' => 'Banco Ripley' ),  
        	array( 'name' => 'Banco Consorcio' ),  
        	array( 'name' => 'Banco Bilbao Vizcaya Argentaria' ),  
        	array( 'name' => 'Banco BBVA' ),  
        	array( 'name' => 'Banco Itaú' ),  
        	array( 'name' => 'Banco Estado' ),  
        	array( 'name' => 'Banco Falabella' ),  
        	array( 'name' => 'Rabobank' ),  
        	array( 'name' => 'Banco Paris' ),    
        	array( 'name' => 'Banco de Chile / Edwards-Citi' ),  
        	array( 'name' => 'Coopeuch' ),  
        	array( 'name' => 'Banco BTG Pactual Chile' )
        );
        DB::table('banks')->insert($banks);
    }
}
