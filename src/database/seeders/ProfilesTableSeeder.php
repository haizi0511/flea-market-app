<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param = [
        'id' => '1',
        'user_id' =>'3',
        'postal_code' => 252-0144,
        'address' => '神奈川県相模原市東橋本2-12-15',
        'building' =>'レヴァンテ102',
        'profile_image' => 'test',
        ];
        DB::table('profiles')->insert($param);
    }
}
