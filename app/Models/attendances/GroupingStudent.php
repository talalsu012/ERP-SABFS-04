<?php

namespace App\Models\attendances;

use App\Models\attendances\Room;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupingStudent extends Pivot
{
    protected $table = 'att_grouping_student';

    protected $fillable = [
        'grouping_id',
        'university_no',
         'batch_code',
         'room_id'
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
    public function grouping(): BelongsTo
    {
        return $this->belongsTo(grouping::class, 'grouping_id', 'id');
    }



}
