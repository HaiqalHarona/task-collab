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
        Schema::table('workspace_members', function (Blueprint $table) {
            $table->foreign(['workspace_id'], 'workspace_members_ibfk_1')->references(['id'])->on('workspaces')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_email'], 'workspace_members_ibfk_2')->references(['email'])->on('user')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workspace_members', function (Blueprint $table) {
            $table->dropForeign('workspace_members_ibfk_1');
            $table->dropForeign('workspace_members_ibfk_2');
        });
    }
};
