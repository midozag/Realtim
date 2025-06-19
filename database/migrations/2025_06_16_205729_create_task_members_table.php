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
        Schema::create('task_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projectId');
            $table->unsignedBigInteger('taskId');
            $table->unsignedBigInteger('memberId');
            $table->timestamps();

            $table->foreign('projectId')
            ->references('id')
            ->on('projects')
            ->onDelete('cascade');

            $table->foreign('taskId')
            ->references('id')
            ->on('tasks')
            ->onDelete('cascade');
 
            $table->foreign('memberId')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_members');
    }
};
