<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 複数カテゴリー用 JSON カラム
            $table->json('categories')->nullable();

            $table->string('name');
            $table->string('brand')->nullable(); // ブランド名追加
            $table->text('description')->nullable();
            $table->integer('price');
            $table->string('condition')->nullable(); // 商品状態
            $table->string('status')->default('available'); // 出品状況
            $table->string('image_path')->nullable(); // 商品画像
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('items');
    }
}
