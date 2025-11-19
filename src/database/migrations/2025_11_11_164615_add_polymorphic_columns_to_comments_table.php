<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 正しいクラス名
class AddPolymorphicColumnsToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('commentable_id')->nullable();
            $table->string('commentable_type')->nullable();
        });

    }

    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn(['commentable_id', 'commentable_type']);
        });
    }
}
