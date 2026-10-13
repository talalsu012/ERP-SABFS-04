<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccEntryItem extends Model
{
    use HasFactory;

    protected $table = 'acc_entry_items';
    protected $guarded = [];

    /**
     * Get the entry this item belongs to
     */
    public function entry()
    {
        return $this->belongsTo(AccEntry::class, 'entry_id');
    }

    /**
     * Get the ledger for this item
     */
    public function ledger()
    {
        return $this->belongsTo(AccLedger::class, 'ledger_id');
    }

    /**
     * Check if this is a debit item
     */
    public function isDebit(): bool
    {
        return $this->dc === 'D';
    }

    /**
     * Check if this is a credit item
     */
    public function isCredit(): bool
    {
        return $this->dc === 'C';
    }

    /**
     * Get signed amount (positive for debit, negative for credit)
     */
    public function getSignedAmountAttribute(): float
    {
        return $this->isDebit() ? $this->amount : -$this->amount;
    }

    /**
     * Check if this item is reconciled
     */
    public function isReconciled(): bool
    {
        return !is_null($this->reconciliation_date);
    }
}
