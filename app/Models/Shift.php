<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_time',
        'end_time',
        'grace_period_minutes',
        'overtime_threshold_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
