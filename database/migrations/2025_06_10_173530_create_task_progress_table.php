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
        Schema::create('task_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projectId');
            $table->boolean('pinned_on_dashboard')->default(false);
            $table->unsignedBigInteger('progress')->default(0);

            $table->foreign('projectId')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_progress');
    }
};
