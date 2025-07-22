<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'balance',
        'payment_methods',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'balance' => 'decimal:2',
            'payment_methods' => 'array',
        ];
    }

    /**
     * Проверяет, является ли пользователь администратором
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Проверяет, является ли пользователь модератором
     */
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    /**
     * Проверяет, имеет ли пользователь определенную роль
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Пополняет баланс пользователя
     */
    public function addBalance(float $amount): void
    {
        $this->increment('balance', $amount);
    }

    /**
     * Списывает средства с баланса пользователя
     */
    public function deductBalance(float $amount): bool
    {
        if ($this->balance >= $amount) {
            $this->decrement('balance', $amount);
            return true;
        }
        return false;
    }

    /**
     * Добавляет способ оплаты
     */
    public function addPaymentMethod(array $paymentMethod): void
    {
        $methods = $this->payment_methods ?? [];
        $methods[] = $paymentMethod;
        $this->update(['payment_methods' => $methods]);
    }

    /**
     * Удаляет способ оплаты
     */
    public function removePaymentMethod(int $index): void
    {
        $methods = $this->payment_methods ?? [];
        if (isset($methods[$index])) {
            unset($methods[$index]);
            $this->update(['payment_methods' => array_values($methods)]);
        }
    }
}
