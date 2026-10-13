<?php

namespace App\Models\Cert;

use App\Models\Reg\{Faculty, Special, Lt_column, Department};
use Illuminate\Support\Str;
use App\Traits\HasVisibleScope;
use Attribute;
use Illuminate\Database\Eloquent\{Model, SoftDeletes, Relations\BelongsTo};
use Spatie\Activitylog\{LogOptions, Traits\LogsActivity};

class CertStudent extends Model
{
    use SoftDeletes, LogsActivity, HasVisibleScope;

    protected $fillable = [
        'gender_code',
        'admission_code',
        'academic_year_code',
        'stady_code',
        'department_code',
        'special_code',
        'universityno',
        'name_ar',
        'name_en',
        'alumia_date',
        'degree_id',
        'grade_id',
        'faculty_code',
        'nationalty_code',
        'meeting_no',
        'remarks',
        'remarks_en',
        'hijri_date',
        'slug',
        'verify_hash',
    ];

    protected $casts = [
        'alumia_date' => 'date',
        'is_visible'  => 'boolean',
    ];

    // Generate verify hash for the certificate
     public function Verify(): void
    {
        $this->verify_hash = hash(
            'sha256',
            $this->name_ar . $this->faculty_code . $this->alumia_date . config('app.key')
        );
        $this->save();
    }



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }


    /* ================= Accessors ================= */

   public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }


    public function getYearAttribute(): ?string
    {
        return $this->alumia_date?->format('Y');
    }

    /* ================= Relations ================= */

    public function degree(): BelongsTo
    {
        return $this->belongsTo(CertSetting::class, 'degree_id')
            ->where('groups', '=', 'degree');
    }
    public function grade(): BelongsTo
    {
        return $this->belongsTo(CertSetting::class, 'grade_id')
            ->where('groups', '=', 'grade');
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(CertSetting::class, 'admission_code')
            ->where('groups', '=', 'admission');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_code');
    }



    /**
     * Get the options for logging activity.
     */


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function special(): BelongsTo
    {
        return $this->belongsTo(Special::class);
    }
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'gender_code')
            ->where('grouped', '=', '1001');
    }

    public function stady(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'stady_code');
    }
    public function academicyear(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'academic_year_code');
    }
    public function nationalty(): BelongsTo
    {
        return $this->belongsTo(Lt_column::class, 'nationalty_code');
    }
}
