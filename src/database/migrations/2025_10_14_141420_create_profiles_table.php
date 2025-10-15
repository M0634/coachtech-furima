<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();        // ユーザー名
            $table->string('postal_code')->nullable(); // 郵便番号
            $table->string('address')->nullable();     // 住所
            $table->string('building')->nullable();    // 建物名
            $table->string('image')->nullable();       // プロフィール画像
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
