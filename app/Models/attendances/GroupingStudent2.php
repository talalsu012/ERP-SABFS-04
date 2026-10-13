<?php

namespace App\Models\attendances;


use App\Models\Reg\Student;
use App\Models\attendances\Room;
use App\Models\attendances\Grouping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupingStudent extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * SELECT lecture_id, universit_no, `status`	FROM att_lecture_student
     */
    protected $table = 'att_grouping_student';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    // public $incrementing = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    // protected $primaryKey = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'grouping_id',
        'university_no',
         'batch_code',
         'room_id'
    ];

    // تعريف العلاقة مع نموذج Room
    public function room(): BelongsTo
    {
        // room_id هو المفتاح الأجنبي في att_grouping_student
        // 'id' هو المفتاح الأساسي في جدول rooms
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    // تعريف العلاقة مع نموذج Grouping (للوضوح، إذا احتجت للوصول للمجموعة من هنا)
    public function grouping(): BelongsTo
    {
        return $this->belongsTo(Grouping::class, 'grouping_id', 'id');
    }

    // تعريف العلاقة مع نموذج Student (للوضوح، إذا احتجت للوصول للطالب من هنا)
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'university_no', 'university_no');
    }

    // public function group()
    // {
    //     return $this->belongsTo(Grouping::class, 'group_id');
    // }

    // public function student()
    // {
    //     return $this->belongsTo(Student::class, 'university_no');
    // }

    // public function batch(): BelongsTo
    // {
    //     return $this->belongsTo(Batch::class, 'batch_code', 'code');
    // }

    // public function room()
    // {
    //     return $this->belongsTo(Room::class, 'room_id');
    // }

}
