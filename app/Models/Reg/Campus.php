<?php

namespace App\Models\Reg;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campus extends Model
{
    use HasFactory;
    use LogsActivity;


    protected $primaryKey = 'code';
    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean'
        ];
    }

    public static function Visible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
         ->logAll();
    }

    public function students() : HasMany
    {
        return $this->hasMany(Student::class);
    }

}
