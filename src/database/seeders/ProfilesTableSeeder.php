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
        'user_id' =>'1',
        'postal_code' => '000-0000',
        'address' => 'テスト1-1-1',
        'building' =>'テスト111',
        'profile_image' => 'test',
        ];
        DB::table('profiles')->insert($param);
    }
}
