<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('postal_code')->nullable(false); // 郵便番号は必須
            $table->string('prefecture')->default('未設定'); // 都道府県は未設定でもOK
            $table->string('city')->default('未設定');       // 市区町村もデフォルト値
            $table->string('street')->default('未設定');     // 町名・番地もデフォルト
            $table->string('building')->nullable();         // 建物名は任意
            $table->string('phone')->default('000-0000-0000'); // 電話番号にデフォルト
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
