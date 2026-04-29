<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_id')->index('project_members_project_id');
            $table->string('user_email')->index('project_members_user_email');
            $table->enum('role', ['owner', 'admin', 'member', 'viewer'])->default('member');
            $table->timestamp('added_at')->nullable()->useCurrent();

            $table->unique(['project_id', 'user_email'], 'project_members_unique');

            $table->foreign('project_id', 'project_members_ibfk_1')
                ->references('id')->on('projects')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('user_email', 'project_members_ibfk_2')
                ->references('email')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_members');
    }
};
