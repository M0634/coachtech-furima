<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // 出品者ユーザーID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 商品画像（複数枚対応は別テーブルにすることも可能）
            $table->string('image')->nullable();

            // カテゴリー（カンマ区切り保存）
            $table->string('categories')->nullable();

            // 商品の状態
            $table->string('condition')->nullable();

            // 商品名
            $table->string('name');

            // ブランド名
            $table->string('brand')->nullable();

            // 商品説明
            $table->text('description')->nullable();

            // 販売価格
            $table->integer('price')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
