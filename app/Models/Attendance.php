<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id', 'date', 'time_in', 'time_out', 'status', 'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getHoursWorkedAttribute()
    {
        if (! $this->time_in || ! $this->time_out) {
            return null;
        }

        return round($this->time_in->diffInMinutes($this->time_out) / 60, 2);
    }
}
