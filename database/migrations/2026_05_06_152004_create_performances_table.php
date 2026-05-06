<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('period');
            $table->decimal('score', 4, 1);
            $table->enum('rating', ['Outstanding', 'Satisfactory', 'Needs Improvement', 'Poor'])->default('Satisfactory');
            $table->text('feedback')->nullable();
            $table->foreignId('reviewed_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performances');
    }
};
