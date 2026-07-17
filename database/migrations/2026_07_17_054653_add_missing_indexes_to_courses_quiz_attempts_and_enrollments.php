<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('quiz_attempt_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('step_id');
        });

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->index('course_id');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('quiz_attempt_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['step_id']);
        });

        Schema::table('course_enrollments', function (Blueprint $table) {
            $table->dropIndex(['course_id']);
        });
    }
};
