<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueFromFavoritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            // まず外部キーを削除
            $table->dropForeign('favorites_user_id_foreign');

            // UNIQUE 制約を削除
            $table->dropUnique('favorites_user_id_item_id_unique');

            // 外部キーを再作成（UNIQUEなし）
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            // down では元に戻す
            $table->dropForeign(['user_id']);
            $table->unique('user_id', 'favorites_user_id_item_id_unique');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
