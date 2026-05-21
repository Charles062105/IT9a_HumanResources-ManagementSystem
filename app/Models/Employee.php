<?php

// ── app/Models/Employee.php ──────────────────────────────

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id', 'user_id', 'first_name', 'last_name', 'email',
        'phone', 'address', 'department', 'position',
        'date_hired', 'date_of_birth', 'contract_expiry',
        'status', 'sss_number', 'pagibig_number', 'philhealth_number',
        'shift_id', 'profile_completed',
    ];

    protected $casts = ['date_hired' => 'date', 'date_of_birth' => 'date', 'contract_expiry' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function violations()
    {
        return $this->hasMany(Violation::class);
    }

    public function performances()
    {
        return $this->hasMany(Performance::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function assignedTimesheets()
    {
        return $this->hasMany(AssignedTimesheet::class);
    }

    public function getFullNameAttribute()
    {
        $first = $this->first_name ?? '';
        $last = $this->last_name ?? '';

        return trim("$first $last") ?: '—';
    }

    public function getInitialsAttribute()
    {
        $f = $this->first_name ? strtoupper(substr($this->first_name, 0, 1)) : '';
        $l = $this->last_name ? strtoupper(substr($this->last_name, 0, 1)) : '';

        return $f.$l ?: '??';
    }

    public function getYearsOfServiceAttribute()
    {
        return Carbon::parse($this->date_hired)->diffInYears(now());
    }

    public function getProfileCompletionPercentageAttribute(): int
    {
        $fields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'date_of_birth',
            'department',
            'position',
            'date_hired',
            'address',
            'sss_number',
            'pagibig_number',
            'philhealth_number',
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (! empty($this->$field)) {
                $completed++;
            }
        }

        return (int) ceil(($completed / count($fields)) * 100);
    }
}
