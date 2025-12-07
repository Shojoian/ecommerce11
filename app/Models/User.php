<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Orders relation for /my-orders
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    // ✅ Simple avatar URL accessor (no Intervention)
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Fallback to ui-avatars if no uploaded avatar
        return 'https://ui-avatars.com/api/?name='
            . urlencode($this->name)
            . '&background=E2E8F0&color=475569';
    }
}
