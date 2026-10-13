<?php

namespace App\Models\Reg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentAccount extends Model
{
    protected $table = 'users_std';
    protected $primaryKey = 'university_no';

    protected $guarded = [];

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
            'parent_alive' => 'boolean',
            'mother_alive' => 'boolean',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'gender_code');
    }

    public function nationalty(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'nationality_code');
    }
    public function state(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'state_code');
    }
}