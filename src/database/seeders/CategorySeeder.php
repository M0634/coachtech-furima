<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'ファッション'],
            ['name' => '家電'],
            ['name' => '食品'],
            ['name' => '日用品'],
            ['name' => 'その他'],
        ]);
    }
}
