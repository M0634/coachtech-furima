<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'brand')) {
                $table->string('brand')->nullable();
            }
            if (!Schema::hasColumn('items', 'img_url')) {
                $table->string('img_url')->nullable();
            }
            if (!Schema::hasColumn('items', 'condition')) {
                $table->string('condition')->nullable();
            }
});

    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['brand', 'description', 'img_url', 'condition']);
        });
    }
};
