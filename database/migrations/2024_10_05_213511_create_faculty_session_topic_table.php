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
        Schema::create('faculty_session_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('session_departments')->cascadeOnDelete();
            $table->foreignId('agenda_id')->constrained('topic_agendas')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_session_topic');
    }
};
