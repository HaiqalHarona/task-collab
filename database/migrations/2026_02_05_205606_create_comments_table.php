<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('task_id')->index('comments_task_id');
            $table->string('user_email')->index('comments_user_email');
            $table->text('body');
            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('task_id', 'comments_ibfk_1')
                ->references('id')->on('tasks')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('user_email', 'comments_ibfk_2')
                ->references('email')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
