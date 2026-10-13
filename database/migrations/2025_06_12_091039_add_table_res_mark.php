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
        Schema::create('res_resons', function (Blueprint $table) {
            $table->unsignedBigInteger('code')
                // ->nullable()
                ->comment('number of the reson for the res marks');
            // Assuming code is the primary key for reson
            $table->string('name', 100);
            $table->string('name_en', 100)->nullable();

            $table->string('slug', 50)->nullable()
                ->comment('slug for the reson');

            $table->string('description', 500)->nullable();

            $table->boolean('is_visible')->default(true)
                ->comment('true: visible, false: not visible');
            $table->timestamps();

            $table->primary('code');
            $table->unique(['name', 'name_en'], 'unique_reson_name');

        });

        Schema::create('res_ltrecoms', function (Blueprint $table) {

            $table->unsignedBigInteger('code')
                ->nullable()
                ->comment('number of the recom for the res marks');
            $table->string('name', 100);

            $table->string('name_en', 100)->nullable();

            $table->string('slug', 50)->nullable()
            ->comment('slug for the recom');

            $table->string('description', 500)->nullable();
            $table->boolean('is_visible')->default(true)
                ->comment('true: visible, false: not visible');
            $table->timestamps();

            $table->primary('code');

            $table->unique(['name', 'name_en'], 'unique_recom_name');
        });

        Schema::create('res_marks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_no')
            ->constrained('students', 'university_no')
                ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');
            // Assuming university_no is the primary key in students table

            $table->foreignId('subject_code')->constrained('subjects', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->foreignId('faculty_code')
                ->constrained('faculties', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->foreignId('level_code')
                ->constrained('levels', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->foreignId('batch_code')
                ->constrained('batches', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->enum('semester', ['1', '2'])
                ->comment('1: First Semester, 2: Second Semester')
                ->default('1'); // Default to first semester

            $table->enum('examType', ['1', '2'])
                ->comment('1: Basic, 2: Absolute')
                ->default('1'); // Default to first exam type

            $table->integer('mark')->nullable(); // Assuming marks can be up to 11
            $table->integer('cwork')->nullable(); // Assuming max marks can be up to 11
            $table->string('grade',20)->nullable(); // Assuming grade can be up to 11
            $table->float('point',2)->nullable(); // Assuming total grade point can be up to 11
            $table->string('note',250)->nullable(); // Assuming remarks can be up to 11

            $table->boolean('is_visible')->default(true)
                ->comment('true: visible, false: not visible');

            $table->boolean('is_locked')->default(false);

            $table->boolean('is_released')->default(false);

            $table->boolean('is_recheck')->default(false);

            $table->boolean('is_recheck_passed')->default(false)
                ->comment('true: passed, false: failed');

            $table->foreignId('reson_code')
                ->nullable()
                ->constrained('res_resons', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('res_marks', function (Blueprint $table) {
            $table->unique(['university_no', 'subject_code', 'level_code', 'batch_code', 'semester', 'examType'], 'unique_marks');
        });
        Schema::table('res_marks', function (Blueprint $table) {
            $table->index(['university_no', 'subject_code', 'level_code', 'batch_code', 'semester', 'examType'], 'marks_index');
        });

        Schema::create('res_recoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_no')
            ->constrained('students', 'university_no')
                ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');
            // Assuming university_no is the primary key in students table

            $table->foreignId('faculty_code')
                ->constrained('faculties', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->foreignId('level_code')
                ->constrained('levels', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->foreignId('batch_code')
                ->constrained('batches', 'code')
                 ->cascadeOnUpdate()
                ->cascadeOnDelete('SET NULL');

            $table->enum('semester', ['1', '2', '3', '4', '5'])
                ->comment('1: First Semester, 2: Second Semester', '3: Third Semester', '4: Fourth Semester', '5: Fifth Semester')
                ->default('1'); // Default to first semester

            $table->enum('examType', ['1', '2'])
                ->comment('1: Basic, 2: Absolute')
                ->default('1'); // Default to first exam type

            $table->integer('cfail')->nullable(); // Assuming marks can be up to 11
            $table->integer('cabs')->nullable(); // Assuming marks can be up to 11
            $table->string('subject_fail',200)->nullable(); // Assuming grade can be up to 11
            $table->string('subject_abs',20)->nullable(); // Assuming grade can be up to 11

            $table->integer('crch')->nullable(); // Assuming marks can be up to 11
            $table->float('points',2)->nullable(); // Assuming total grade point can be up to 11
            $table->float('pga',2)->nullable(); // Assuming total grade point can be up to 11
            $table->float('cpga',2)->nullable(); // Assuming total grade point can be up to 11

            $table->foreignId('recom_code')
            ->nullable()
            ->constrained('res_ltrecoms', 'code')
             ->cascadeOnUpdate()
            ->cascadeOnDelete('SET NULL');

            $table->string('note',250)->nullable(); // Assuming remarks can be up to 11

            $table->boolean('is_visible')->default(true)
                ->comment('true: visible, false: not visible');

            $table->boolean('is_locked')->default(false);
            $table->boolean('is_released')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('res_resons');
        Schema::dropIfExists('res_marks');
        Schema::dropIfExists('res_ltrecoms');
        Schema::dropIfExists('res_recoms');
    }
};
