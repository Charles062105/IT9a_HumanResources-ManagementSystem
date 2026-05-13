<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timesheet extends Model
{
    protected $fillable = [
        'employee_id', 'week_start', 'week_end', 'week_label',
        'total_hours', 'ot_hours', 'notes', 'rejection_reason', 'status', 'approved_by', 'submitted_at',
        'assigned_timesheet_id',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignedTimesheet(): BelongsTo
    {
        return $this->belongsTo(AssignedTimesheet::class);
    }
}
