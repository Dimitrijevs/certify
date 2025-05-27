<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Support\Facades\Auth;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;

class User extends Authenticatable implements HasAvatar, FilamentUser
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'role_id',
        'school_id',
        'group_id',
        'country',
        'password',
        'stripe_connect_id',
        'completed_stripe_onboarding',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'completed_stripe_onboarding' => 'boolean',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class, 'created_by');;
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function certification_requirement()
    {
        return $this->hasMany(LearningCertificationRequirement::class, 'entity_id')
            ->where('entity_type', 'student');
    }

    public function purchases()
    {
        return $this->hasMany(UserPurchase::class);
    }

    public function certificates()
    {
        return $this->hasMany(LearningCertificate::class);
    }

    public function isCreatedByAuthUser($school)
    {
        return Auth::user()->id === $school->created_by;
    }
}
