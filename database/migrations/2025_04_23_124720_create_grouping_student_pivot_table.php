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
        Schema::create('att_grouping_student', function (Blueprint $table) {
            $table->unsignedBigInteger('grouping_id');
            $table->unsignedBigInteger('university_no')->nullable();

            $table->foreign('grouping_id')->references('id')->on('att_grouping')->onDelete('cascade');
            $table->foreign('university_no')->references('university_no')->on('students')->onDelete('cascade');

            $table->primary(['grouping_id', 'university_no']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('att_grouping_student');
    }
};
