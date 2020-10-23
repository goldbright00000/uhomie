<?php

use Illuminate\Database\Seeder;
use App\Sasapplicant;
use Carbon\Carbon;

class SasapplicantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nuevo = new Sasapplicant();
        //$nuevo->token = 'tokenprueba';
        $nuevo->applicant_id = '5cb744200a975a67ed1798a4';
        $nuevo->user_id = 1;
        //$nuevo->created_at = 
        $nuevo->save();
    }
}
