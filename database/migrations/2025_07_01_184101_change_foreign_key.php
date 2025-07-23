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
        Schema::table('task_members', function (Blueprint $table) {
            $table->dropForeign(['memberId']);
            $table->foreign('memberId')
            ->references('id')
            ->on('memebers')
            ->onDelete('cascade')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_members', function (Blueprint $table) {
            $table->dropForeign(['memberId']);
            $table->foreign('memberId')
            ->references('id')
            ->on('memebers')
            ->onDelete('cascade')
            ;
        });
    }
};
