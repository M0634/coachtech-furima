<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        // ここに DB::statement を書きます
        DB::statement('ALTER TABLE purchases MODIFY item_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE purchases ADD COLUMN product_id BIGINT UNSIGNED NULL AFTER item_id');
        DB::statement('ALTER TABLE purchases ADD CONSTRAINT purchases_product_id_foreign FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        // 元に戻す処理も書きます
        DB::statement('ALTER TABLE purchases DROP FOREIGN KEY purchases_product_id_foreign');
        DB::statement('ALTER TABLE purchases DROP COLUMN product_id');
        DB::statement('ALTER TABLE purchases MODIFY item_id BIGINT UNSIGNED NOT NULL');
    }
};
