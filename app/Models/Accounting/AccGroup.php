<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccGroup extends Model
{
    use HasFactory;

    protected $table = 'acc_groups';
    protected $guarded = [];

    /**
     * Get the parent group
     */
    public function parent()
    {
        return $this->belongsTo(AccGroup::class, 'parent_id');
    }

    /**
     * Get the child groups
     */
    public function children()
    {
        return $this->hasMany(AccGroup::class, 'parent_id');
    }

    /**
     * Get the ledgers in this group
     */
    public function ledgers()
    {
        return $this->hasMany(AccLedger::class, 'group_id');
    }

    /**
     * Check if this group affects gross profit
     */
    public function affectsGross(): bool
    {
        return $this->affects_gross;
    }
}
