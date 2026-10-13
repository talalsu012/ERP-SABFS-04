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
        Schema::create('att_grouping_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('att_rooms')->onDelete('cascade');
            $table->foreignId('grouping_id')->constrained('att_grouping')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('att_grouping_rooms');
    }
};
