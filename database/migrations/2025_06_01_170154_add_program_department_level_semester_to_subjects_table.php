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
        Schema::table('subjects', function (Blueprint $table) {
                // Add faculty_code foreign key
                $table->unsignedBigInteger('faculty_code')->nullable();
                $table->foreign('faculty_code')
                      ->references('code')
                      ->on('faculties')
                      ->onDelete('set null');

                // Add department_code foreign key
                $table->unsignedBigInteger('department_code')->nullable()
                ->after('faculty_code');
                $table->foreign('department_code')
                      ->references('code')
                      ->on('departments')
                      ->onDelete('set null');

                // Add level_id foreign key
                $table->unsignedBigInteger('level_code')->nullable()->after('department_code');
                $table->foreign('level_code')
                      ->references('code')
                      ->on('levels')
                      ->onDelete('set null');

                // Add semester_id
                $table->enum('semester_id', [1,2] )
                    ->nullable()
                    ->after('level_code');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['faculty_code']);
            $table->dropColumn('faculty_code');

            $table->dropForeign(['department_code']);
            $table->dropColumn('department_code');

            $table->dropForeign(['level_code']);
            $table->dropColumn('level_code');

            $table->dropColumn('semester_id');
        });
    }
};
