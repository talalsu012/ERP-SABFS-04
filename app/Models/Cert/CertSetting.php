<?php

namespace App\Models\Cert;

use App\Models\Reg\Faculty;
use App\Traits\HasVisibleScope;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertSetting extends Model
{
    //
    use LogsActivity;
    use HasVisibleScope;

    protected $table = 'cert_setting';
    protected $fillable = [
        'name_ar', 'name_en', 'slug', 'groups',
        'faculty_code', 'parent_id', 'is_visible',
        ];


    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_code');
    }

    public function children(): HasMany
    {
        return $this->hasMany(CertSetting::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CertSetting::class);
    }
}
