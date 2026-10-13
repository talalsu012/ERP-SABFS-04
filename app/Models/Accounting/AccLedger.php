<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccLedger extends Model
{
    use HasFactory;

    protected $table = 'acc_ledgers';
    protected $guarded = [];

    /**
     * Get the group this ledger belongs to
     */
    public function group()
    {
        return $this->belongsTo(AccGroup::class, 'group_id');
    }

    /**
     * Get all entry items for this ledger
     */
    public function entryItems()
    {
        return $this->hasMany(AccEntryItem::class, 'ledger_id');
    }

    /**
     * Get the opening balance with sign
     */
    public function getOpeningBalanceAttribute(): float
    {
        return $this->op_balance_dc === 'D'
            ? $this->op_balance
            : -$this->op_balance;
    }

    /**
     * Check if this ledger requires reconciliation
     */
    public function requiresReconciliation(): bool
    {
        return $this->reconciliation;
    }
}
