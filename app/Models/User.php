<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'is_platform_admin',
        'organization_key',
        'module_access',
        'microsoft_id',
        'microsoft_email',
        'microsoft_access_token',
        'microsoft_refresh_token',
        'microsoft_token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'microsoft_access_token',
        'microsoft_refresh_token',
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
            'is_platform_admin' => 'boolean',
            'module_access' => 'array',
            'microsoft_token_expires_at' => 'datetime',
        ];
    }

    public function isPlatformAdmin(): bool
    {
        return (bool) $this->is_platform_admin;
    }

    public function canAccessModule(string $module): bool
    {
        if ($this->isPlatformAdmin()) {
            return true;
        }

        return in_array($module, $this->module_access ?? [], true);
    }

    public function canAccessOrganization(string $organizationKey): bool
    {
        return $this->isPlatformAdmin()
            || hash_equals((string) $this->organization_key, $organizationKey);
    }
}
