<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_assignees', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id');
            $table->string('user_email')->index('task_assignees_user_email');

            $table->primary(['task_id', 'user_email']);

            $table->foreign('task_id', 'task_assignees_ibfk_1')
                ->references('id')->on('tasks')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('user_email', 'task_assignees_ibfk_2')
                ->references('email')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_assignees');
    }
};
