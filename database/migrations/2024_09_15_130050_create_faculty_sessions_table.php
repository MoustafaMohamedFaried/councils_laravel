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
        Schema::create('faculty_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('order');
            $table->string('place');
            $table->foreignId('faculty_id')->constrained('faculties', 'id')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('responsible_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->integer('status')->default(0)->comment('0=>pending, 1=>accepted, 2=>rejected, 3=>rejected with reason');
            $table->string('reject_reason')->nullable();
            $table->integer('decision_by')->default(0)->comment('0=>members, 1=>secratery of department council');
            $table->dateTime('start_time');
            $table->integer('total_hours');
            $table->dateTime('schedual_end_time');
            $table->dateTime('actual_start_time')->nullable();
            $table->dateTime('actual_end_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_sessions');
    }
};
