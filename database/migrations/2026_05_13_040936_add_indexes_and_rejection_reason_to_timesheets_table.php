<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('notes');
            $table->index('status');
            $table->index('week_label');
            $table->index(['employee_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('timesheets', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
            $table->dropIndex(['status']);
            $table->dropIndex(['week_label']);
            $table->dropIndex(['employee_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
        });
    }
};
