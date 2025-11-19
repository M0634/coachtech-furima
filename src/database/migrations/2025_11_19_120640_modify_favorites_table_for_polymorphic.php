<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('favorites', function (Blueprint $table) {
            // item_id は不要になるので削除
            if (Schema::hasColumn('favorites', 'item_id')) {
                $table->dropForeign(['item_id']);
                $table->dropColumn('item_id');
            }

            // favoritable_type / favoritable_id を not null に変更
            $table->unsignedBigInteger('favoritable_id')->nullable(false)->change();
            $table->string('favoritable_type')->nullable(false)->change();

            // ユーザー x 対象ユニーク制約を作る
            $table->unique(['user_id', 'favoritable_id', 'favoritable_type']);
        });
    }

    public function down()
    {
        Schema::table('favorites', function (Blueprint $table) {
            $table->bigInteger('item_id')->unsigned()->after('user_id');
            $table->dropUnique(['user_id', 'favoritable_id', 'favoritable_type']);
            $table->unsignedBigInteger('favoritable_id')->nullable()->change();
            $table->string('favoritable_type')->nullable()->change();
        });
    }
};
