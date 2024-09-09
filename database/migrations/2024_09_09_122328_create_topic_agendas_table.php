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
        Schema::create('topic_agendas', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('order');
            $table->string('name');
            $table->integer('status')->default(0)->comment('0 = pending, 1 = accepted, 2 = rejected, 3 = rejected with notes');
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_agendas');
    }
};
