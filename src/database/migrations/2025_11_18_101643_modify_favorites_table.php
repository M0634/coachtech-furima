<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // // Schema::table('favorites', function (Blueprint $table) {
        // //     $table->dropUnique('favorites_user_id_item_id_unique'); // 制約名に注意
        // });
    }

    public function down(): void
    {
        // Schema::table('favorites', function (Blueprint $table) {
        //     $table->unique(['user_id', 'item_id']);
        // });
    }
};
