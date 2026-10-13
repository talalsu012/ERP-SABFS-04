<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;
use App\Models\Reg\Faculty;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable //implements FilamentUser
{
    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return $this->is_admin; // or your permission logic
    // }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,  HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */



    protected $fillable = [
        'name',
        'email',
        'password',
        'faculty_code',
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
            'faculty_code' => 'array',
        ];
    }

    public function faculties():BelongsToMany
    {
        return $this->belongsToMany(Faculty::class, 'faculty_user', 'user_id', 'faculty_code');
    }

    public function getFacultyCodes(): array
    {
        return $this->faculties()->pluck('code')->toArray();
    }

}
