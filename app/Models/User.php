<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role Constants
    const ROLE_SUPER_ADMIN = 'super_admin';

    const ROLE_SUB_ADMIN = 'sub_admin';

    const ROLE_EMPLOYEE = 'employee';

    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    // Role checking methods
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isSubAdmin(): bool
    {
        return $this->role === self::ROLE_SUB_ADMIN;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_SUB_ADMIN]);
    }

    public function isEmployee(): bool
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    // Permission checking method
    public function hasPermission(string $permissionName): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin has all permissions
        }

        return RolePermission::where('role', $this->role)
            ->whereHas('permission', fn ($q) => $q->where('name', $permissionName))
            ->exists();
    }

    // Check multiple permissions (any match)
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $perm) {
            if ($this->hasPermission($perm)) {
                return true;
            }
        }

        return false;
    }

    // Check multiple permissions (all match)
    public function hasAllPermissions(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $perm) {
            if (! $this->hasPermission($perm)) {
                return false;
            }
        }

        return true;
    }
}
