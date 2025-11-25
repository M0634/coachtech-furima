<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 外部キー制約を一時的に無効化（今回は不要かもしれません）
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // カテゴリを JSON で更新
        DB::table('items')->where('name', 'like', '%時計%')
            ->update(['categories' => json_encode([1])]);

        DB::table('items')->where(function ($query) {
            $query->where('name', 'like', '%PC%')
                  ->orWhere('name', 'like', '%HDD%')
                  ->orWhere('name', 'like', '%パソコン%');
        })
            ->update(['categories' => json_encode([2])]);

        DB::table('items')->where('name', 'like', '%玉ねぎ%')
            ->update(['categories' => json_encode([3])]);

        DB::table('items')->where(function ($query) {
            $query->where('name', 'like', '%靴%')
                  ->orWhere('name', 'like', '%バッグ%');
        })
            ->update(['categories' => json_encode([4])]);

        DB::table('items')->where(function ($query) {
            $query->where('name', 'like', '%マイク%')
                  ->orWhere('name', 'like', '%コーヒーミル%')
                  ->orWhere('name', 'like', '%タンブラー%')
                  ->orWhere('name', 'like', '%メイク%');
        })
            ->update(['categories' => json_encode([5])]);

        // 外部キー制約を再有効化（必要ないかも）
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
