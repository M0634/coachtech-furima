<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 既存テーブルを削除（必要に応じてデータバックアップしてください）
        Schema::dropIfExists('addresses');

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('postal_code');  // 郵便番号
            $table->string('address');      // 住所（都道府県＋市区町村＋丁目・番地）
            $table->string('building')->nullable(); // 建物名（任意）
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
