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
        Schema::create('faculty_headquarter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties','id')->cascadeOnDelete();
            $table->foreignId('headquarter_id')->constrained('headquarters','id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_headquarter');
    }
};
