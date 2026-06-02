<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
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
        'avatar',
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
        ];
    }

    public function roleName(): string
    {
        return $this->role ?? 'user';
    }

    public function isAdmin(): bool
    {
        return $this->roleName() === 'admin';
    }

    public function isExpert(): bool
    {
        return $this->roleName() === 'expert';
    }

    /** Доступ к /admin (эксперт или администратор). */
    public function canAccessAdminPanel(): bool
    {
        return $this->isAdmin() || $this->isExpert();
    }

    /** Создание заданий (эксперт или администратор). */
    public function canManageTasks(): bool
    {
        return $this->canAccessAdminPanel();
    }

    /** Просмотр списка и добавление пользователей. */
    public function canManageUsers(): bool
    {
        return $this->canAccessAdminPanel();
    }

    /** Удаление пользователей — только администратор. */
    public function canDeleteUsers(): bool
    {
        return $this->isAdmin();
    }

    public function roleLabel(): string
    {
        return match ($this->roleName()) {
            'admin' => 'Администратор',
            'expert' => 'Эксперт',
            default => 'Пользователь',
        };
    }

    public function taskSubmissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
