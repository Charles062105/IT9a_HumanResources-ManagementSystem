<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    protected $fillable = [
        'employee_id', 'week_start', 'week_end', 'week_label',
        'total_hours', 'ot_hours', 'notes', 'status', 'approved_by', 'submitted_at',
        'assigned_timesheet_id',
    ];

    protected $casts = [
        'week_start' => 'date',
        'week_end' => 'date',
        'submitted_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function assignedTimesheet()
    {
        return $this->belongsTo(AssignedTimesheet::class);
    }
}
