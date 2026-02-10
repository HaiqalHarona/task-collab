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
        Schema::table('task_tags', function (Blueprint $table) {
            $table->foreign(['task_id'], 'task_tags_ibfk_1')->references(['id'])->on('tasks')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['tag_id'], 'task_tags_ibfk_2')->references(['id'])->on('tags')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_tags', function (Blueprint $table) {
            $table->dropForeign('task_tags_ibfk_1');
            $table->dropForeign('task_tags_ibfk_2');
        });
    }
};
