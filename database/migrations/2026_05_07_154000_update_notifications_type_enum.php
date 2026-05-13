<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run the enum modification for MySQL
        if (DB::getDriverName() === 'mysql') {
            // First, modify the enum to include both old and new values
            DB::statement("ALTER TABLE hrms_notifications MODIFY type ENUM('success', 'warning', 'danger', 'error', 'info') DEFAULT 'info'");

            // Update existing 'danger' values to 'error' — wrapped in a transaction so a failure is atomic
            // If this fails (lock timeout, deadlock), the exception surfaces before the second ALTER
            DB::transaction(function () {
                DB::update("UPDATE hrms_notifications SET type = 'error' WHERE type = 'danger'");
            });

            // Now modify the enum to remove 'danger'
            DB::statement("ALTER TABLE hrms_notifications MODIFY type ENUM('success', 'warning', 'error', 'info') DEFAULT 'info'");
        } else {
            // For SQLite and other databases, just update the values (they don't enforce enums strictly)
            DB::transaction(function () {
                DB::update("UPDATE hrms_notifications SET type = 'error' WHERE type = 'danger'");
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * WARNING: Rollback will corrupt data in production.
     * This migration converts 'danger' → 'error'. During rollback, ALL rows with type = 'error'
     * (including legitimately-inserted 'error' rows by the app after migration) will be converted back to 'danger'.
     * There is no SQL-level way to distinguish pre-migration vs post-migration inserts.
     * After this migration runs and the app creates 'error' notifications, rollback will silently corrupt those rows.
     * Treat this migration as one-way — do not rollback in production after application usage.
     */
    public function down(): void
    {
        // Only run the enum modification for MySQL
        if (DB::getDriverName() === 'mysql') {
            // First, modify the enum to include both old and new values
            DB::statement("ALTER TABLE hrms_notifications MODIFY type ENUM('success', 'warning', 'danger', 'error', 'info') DEFAULT 'info'");

            // WARNING: This will convert ALL rows with type = 'error' back to 'danger',
            // including any rows inserted as 'error' by the application after this migration ran.
            // See class docblock above.
            // Wrapped in a transaction so a failure is atomic.
            DB::transaction(function () {
                DB::update("UPDATE hrms_notifications SET type = 'danger' WHERE type = 'error'");
            });

            // Restore the old enum
            DB::statement("ALTER TABLE hrms_notifications MODIFY type ENUM('success', 'warning', 'danger', 'info') DEFAULT 'info'");
        } else {
            // For SQLite and other databases, just update the values
            // WARNING: This will convert ALL rows with type = 'error' back to 'danger',
            // including any rows inserted as 'error' by the application after this migration ran.
            // See class docblock above.
            // Wrapped in a transaction so a failure is atomic.
            DB::transaction(function () {
                DB::update("UPDATE hrms_notifications SET type = 'danger' WHERE type = 'error'");
            });
        }
    }
};
