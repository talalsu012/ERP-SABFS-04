<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccEntry extends Model
{
    use HasFactory;

    protected $table = 'acc_entries';
    protected $guarded = [];

    /**
     * Get all items for this entry
     */
    public function items()
    {
        return $this->hasMany(AccEntryItem::class, 'entry_id');
    }

    /**
     * Get the debit items
     */
    public function debitItems()
    {
        return $this->items()->where('dc', 'D');
    }

    /**
     * Get the credit items
     */
    public function creditItems()
    {
        return $this->items()->where('dc', 'C');
    }

    /**
     * Check if entry is balanced (debits = credits)
     */
    public function isBalanced(): bool
    {
        return $this->dr_total === $this->cr_total;
    }

    /**
     * Scope for possessed entries
     */
    public function scopePossessed($query)
    {
        return $query->where('possessed', true);
    }
}
