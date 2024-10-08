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
        Schema::create('session_department_decision_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('session_departments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('decision_id')->constrained('session_department_decisions')->cascadeOnDelete();
            $table->integer('status')->comment('1=>accept, 2=>reject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_department_decision_votes');
    }
};
