<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmsNotification extends Model
{
    protected $fillable = ['title', 'message', 'type', 'is_read', 'user_id'];

    protected $casts = ['is_read' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }

    public function getIconBgAttribute(): string
    {
        return match($this->type) {
            'success' => '#F0FDF4',
            'warning' => '#FFFBEB',
            'danger'  => '#FEF2F2',
            'info'    => '#EFF6FF',
            default   => '#F8F9FB',
        };
    }

    public function getIconColorAttribute(): string
    {
        return match($this->type) {
            'success' => '#16A34A',
            'warning' => '#D97706',
            'danger'  => '#DC2626',
            'info'    => '#2563EB',
            default   => '#94A3B8',
        };
    }
}
