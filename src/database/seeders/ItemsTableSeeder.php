<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $param = [
      'item_image' => 'test',
      'category_id' => 1,
      'condition_id' => 1,
      'item_name' => 'test',
      'brand_name' => 'test',
      'item_detail' => 'test',
      'price' => '10',
      'user_id' => 1,
    ];
    DB::table('items')->insert($param);
    $param = [
      'item_image' => 'test',
      'category_id' => 1,
      'condition_id' => 1,
      'item_name' => 'test',
      'brand_name' => 'test',
      'item_detail' => 'test',
      'price' => '10',
      'user_id' => 1,
    ];
    DB::table('items')->insert($param);
    }
}
