<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('tag_id')->index('task_tags_tag_id');

            $table->primary(['task_id', 'tag_id']);

            $table->foreign('task_id', 'task_tags_ibfk_1')
                ->references('id')->on('tasks')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('tag_id', 'task_tags_ibfk_2')
                ->references('id')->on('tags')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_tags');
    }
};
