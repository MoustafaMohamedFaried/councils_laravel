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
        Schema::create('session_department_decisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('session_departments')->cascadeOnDelete();
            $table->foreignId('agenda_id')->constrained('topic_agendas')->cascadeOnDelete();
            $table->string('decision');
            $table->integer('approval')->nullable()->comment('(status from head of department decision) 1=>approve, 2=>reject');
            $table->integer('decision_status')->nullable()->comment( '(total vote of decision) 1=>approve from all, 2=>reject from all, 3=>approve almost, 4=>reject almost, 5=>equal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_department_decisions');
    }
};
