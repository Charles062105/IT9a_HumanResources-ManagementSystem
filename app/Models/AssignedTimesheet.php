<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignedTimesheet extends Model
{
    protected $fillable = [
        'employee_id', 'title', 'description', 'expected_hours',
        'due_date', 'status', 'admin_notes', 'approved_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}
