<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public static function log(
        string $action,
        Model $model,
        ?array $changes = null,
        ?string $description = null,
        ?string $ipAddress = null
    ): AuditLog {
        return AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => $action, // create, update, delete, deactivate, activate
            'model_type' => $model::class,
            'model_id' => $model->id,
            'changes' => $changes,
            'description' => $description,
            'ip_address' => $ipAddress ?? request()->ip(),
        ]);
    }

    public static function logCreate(Model $model, ?string $description = null): AuditLog
    {
        return self::log('create', $model, null, $description);
    }

    public static function logUpdate(Model $model, array $oldValues, ?string $description = null): AuditLog
    {
        return self::log('update', $model, [
            'old' => $oldValues,
            'new' => $model->getAttributes(),
        ], $description);
    }

    public static function logDelete(Model $model, ?string $description = null): AuditLog
    {
        return self::log('delete', $model, null, $description);
    }

    public static function logDeactivate(Model $model, ?string $description = null): AuditLog
    {
        return self::log('deactivate', $model, null, $description);
    }

    public static function logActivate(Model $model, ?string $description = null): AuditLog
    {
        return self::log('activate', $model, null, $description);
    }
}
