<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('level', ['Verbal Warning', 'Written Warning', 'Final Warning', 'Suspension', 'Termination'])->default('Verbal Warning');
            $table->string('offense');
            $table->text('description')->nullable();
            $table->date('date');
            $table->integer('offense_count')->default(1);
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->foreignId('issued_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
