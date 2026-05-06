<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'employee_id', 'level', 'offense', 'description',
        'date', 'status', 'offense_count', 'issued_by',
    ];

    protected $casts = ['date' => 'date'];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function issuer()   { return $this->belongsTo(User::class, 'issued_by'); }

    public function getLevelBadgeColorAttribute(): array
    {
        return match($this->level) {
            'Verbal Warning'  => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'label' => 'V' . $this->offense_count],
            'Written Warning' => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'W' . $this->offense_count],
            'Final Warning'   => ['bg' => '#FFEDD5', 'text' => '#C2410C', 'label' => 'F' . $this->offense_count],
            'Suspension'      => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'label' => 'S' . $this->offense_count],
            'Termination'     => ['bg' => '#1F2937', 'text' => '#F9FAFB', 'label' => 'T'],
            default           => ['bg' => '#F1F5F9', 'text' => '#475569', 'label' => (string)$this->offense_count],
        };
    }
}
