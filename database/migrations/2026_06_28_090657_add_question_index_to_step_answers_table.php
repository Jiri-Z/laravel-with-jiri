<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('step_answers', function (Blueprint $table) {
            $table->unsignedSmallInteger('question_index')->default(0)->after('step_id');

            $table->dropUnique(['user_id', 'step_id']);
            $table->unique(['user_id', 'step_id', 'question_index']);
        });
    }

    public function down(): void
    {
        Schema::table('step_answers', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'step_id', 'question_index']);
            $table->unique(['user_id', 'step_id']);

            $table->dropColumn('question_index');
        });
    }
};
