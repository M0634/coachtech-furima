<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // item_idをnullableに変更
            $table->unsignedBigInteger('item_id')->nullable()->change();

            // product_idがまだ存在しなければ追加
            if (!Schema::hasColumn('purchases', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('item_id');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // product_idが存在すれば削除
            if (Schema::hasColumn('purchases', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }

            // item_idをnullable解除
            $table->unsignedBigInteger('item_id')->nullable(false)->change();
        });
    }
};
