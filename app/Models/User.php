<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'avatar',
        'is_active', 'job_title', 'department', 'joined_date',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'joined_date'       => 'date',
    ];

    // ---- Relationships ----
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // ---- Permission Helpers ----
    public function hasRole(string $role): bool
    {
        return $this->roles->pluck('name')->contains($role);
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }

    public function hasPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) return true;
        }
        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('doctor');
    }

    public function isCustomerService(): bool
    {
        return $this->hasRole('customer_service');
    }

    public function getRoleLabelAttribute(): string
    {
        return $this->roles->pluck('label')->implode('، ') ?: 'بدون دور';
    }

    public function getRoleColorAttribute(): string
    {
        return $this->roles->first()?->color ?? 'secondary';
    }
}
