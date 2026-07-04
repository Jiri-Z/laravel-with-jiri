<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trivia_attempts', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->index('course_id');
        });

        Schema::table('steps', function (Blueprint $table) {
            $table->index('lesson_id');
        });
    }

    public function down(): void
    {
        Schema::table('trivia_attempts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropIndex(['course_id']);
        });

        Schema::table('steps', function (Blueprint $table) {
            $table->dropIndex(['lesson_id']);
        });
    }
};
