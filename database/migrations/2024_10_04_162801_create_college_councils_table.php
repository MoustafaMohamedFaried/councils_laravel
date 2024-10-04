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
        Schema::create('college_councils', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('session_departments')->cascadeOnDelete();
            $table->unsignedBigInteger('agenda_id')->nullable();
            $table->integer('status')->default(0)->comment('0=>pending, 1=accepted, 2=rejected, 3=>rejected with reason');
            $table->string('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_councils');
    }
};
