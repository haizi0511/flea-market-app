<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            'id' => 1,
            'payment_method' => 'コンビニ支払い',
        ]);

        DB::table('payment_methods')->insert([
            'id' => 2,
            'payment_method' => 'カード支払い',
        ]);
    }
}
