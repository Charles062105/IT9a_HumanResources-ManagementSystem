<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    protected $fillable = [
        'employee_id', 'period', 'score', 'rating', 'feedback', 'reviewed_by',
    ];

    public function employee() { return $this->belongsTo(Employee::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function getScorePctAttribute(): int
    {
        return (int) round($this->score * 10);
    }
}
