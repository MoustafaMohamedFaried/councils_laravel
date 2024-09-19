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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->foreignId('headquarter_id')->nullable()->constrained('headquarters','id')->cascadeOnDelete();
            $table->foreignId('faculty_id')->nullable()->constrained('faculties','id')->cascadeOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions','id')->cascadeOnDelete();
            $table->integer('is_active')->comment('0 = disabled, 1 = active, 2 = pending to accept from head of department');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
