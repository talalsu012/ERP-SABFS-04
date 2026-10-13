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
        Schema::create('att_lectures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_code')->constrained('subjects', 'code');
            $table->foreignId('staff_id')->constrained('att_staff');
            $table->foreignId('room_id')->constrained('att_rooms');
            $table->foreignId('grouping_id')->constrained('att_grouping');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('day_of_week'); // Monday, Tuesday, etc.
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });

        Schema::create('att_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecture_id')->constrained('att_lectures');
            $table->date('date');
            $table->time('time');
            $table->foreignId('university_no')->constrained('students', 'university_no');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('att_staff');
            $table->timestamps();

            $table->unique(['lecture_id', 'date', 'university_no']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('att_lectures');
        Schema::dropIfExists('att_attendances');
    }
};
