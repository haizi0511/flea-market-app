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
      [
        'item_image' => 'test1',
        'condition' => '状態が悪い',
        'item_name' => 'test1',
        'brand_name' => 'test1',
        'item_detail' => 'テストです',
        'price' => '1000',
        'user_id' => 1,
      ],
      [
        'item_image' => 'test2',
        'condition' => '良好',
        'item_name' => 'test2',
        'brand_name' => 'test2',
        'item_detail' => 'テストです',
        'price' => '2000',
        'user_id' => 1,
      ]
    ];

    DB::table('items')->insert($param);
    }
}
