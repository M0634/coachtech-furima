<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPurchasesTableForAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // 外部キー & カラム削除
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');

            // 代わりに住所情報を直で保存
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('building')->nullable();
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // 元に戻す場合（念のため）
            $table->foreignId('address_id')->constrained()->onDelete('cascade');

            $table->dropColumn(['postal_code', 'address', 'building']);
        });
    }
}
