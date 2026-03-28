<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'テスト',
            'email' => 'test@example.co.jp',
            'password' => '00000000',
            'profile_completed' => 0,
        ]);
    }
}
