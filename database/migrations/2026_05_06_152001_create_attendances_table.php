<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->dateTime('time_in')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'half_day', 'on_leave'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent duplicate time-in for same employee on same day
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
