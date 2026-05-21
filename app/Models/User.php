<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'phone',
    'address',
    'gender',
    'role',
    'is_online',
    'last_active_at',
    'email',
    'password',
    'password_plain',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_USER = 'user';
    public const ROLE_ADMIN_KASIR = 'admin_kasir';
    public const ROLE_OWNER = 'owner';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_online' => 'boolean',
            'last_active_at' => 'datetime',
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isAdminKasir(): bool
    {
        return $this->role === self::ROLE_ADMIN_KASIR;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
