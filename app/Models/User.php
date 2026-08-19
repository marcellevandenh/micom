<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'super',
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
            'super' => 'boolean',
        ];
    }

    public function getRedirectRoute()
    {
        Log::debug('User->getRedirectRoute: ' . (string)$this->user_type);
        return match((string)$this->user_type) {
            "ADMIN" => '/cp', //see php artisan route:list
            // "ADMIN" => 'backpack.dashboard', //see php artisan route:list
            "CUSTOMER" => 'dashboard', //must be named in the routes
        };
    }
}
