<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'is_verified', 'is_rejected', 'rejection_reason', 'is_suspended', 'wallet_balance'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_rejected' => 'boolean',
            'is_suspended' => 'boolean',
            'wallet_balance' => 'decimal:2',
        ];
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class, 'developer_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function libraries(): HasMany
    {
        return $this->hasMany(Library::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDeveloper(): bool
    {
        return $this->role === 'developer';
    }

    public function isPlayer(): bool
    {
        return $this->role === 'player';
    }
}
