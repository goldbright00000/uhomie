<?php

use Illuminate\Database\Seeder;

class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payments = array(
            array(
                "order" => "order",
                "user_id" => 1,
                "status" => 1,
                "amount" => 1200,
                "iva" => 12,
                "total" => 2000,
                "method" => "debito",
                "currency" => "currency",
                "exchange_rate" => 122,
                "details" => "payment 1 details"
            )
        );
        DB::table('payments')->insert($payments);
    }
}
