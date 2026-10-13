<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Groups table
        Schema::create('acc_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name', 100);
            $table->boolean('affects_gross')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('acc_groups')
                ->onDelete('set null');
        });

        // acc_ledgers table
        Schema::create('acc_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('name', 100);
            $table->decimal('op_balance', 15, 2)->default(0);
            $table->char('op_balance_dc', 1)->default('D'); // D for Debit, C for Credit
            $table->integer('type')->nullable();
            $table->boolean('reconciliation')->default(false);
            $table->timestamps();

            $table->foreign('group_id')
                    ->references('id')->on('acc_groups')
                    ->onDelete('set null');
        });

        // acc_entries table (Journal acc_entries)
        Schema::create('acc_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('entry_type');
            $table->integer('number')->nullable();
            $table->dateTime('date');
            $table->decimal('dr_total', 15, 2)->default(0);
            $table->decimal('cr_total', 15, 2)->default(0);
            $table->longText('narration');
            $table->boolean('possessed')->default(false);
            $table->string('account', 20)->default('');
            $table->integer('src')->nullable()->default(0);
            $table->timestamps();

            $table->index(['id', 'account']);
        });

        // Entry Items table (Journal Entry Items)
        Schema::create('acc_entry_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id');
            $table->unsignedBigInteger('ledger_id');
            $table->decimal('amount', 15, 2)->default(0);
            $table->char('dc', 1); // D for Debit, C for Credit
            $table->dateTime('reconciliation_date')->nullable();
            $table->string('account', 20)->default('');
            $table->timestamps();

            $table->foreign('entry_id')->references('id')->on('acc_entries')->onDelete('cascade');
            $table->foreign('ledger_id')->references('id')->on('acc_ledgers')->onDelete('restrict');
            $table->index(['id', 'account']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_entry_items');
        Schema::dropIfExists('acc_entries');
        Schema::dropIfExists('acc_ledgers');
        Schema::dropIfExists('acc_groups');
    }
};
