<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trivia_questions', function (Blueprint $table) {
            $table->string('locale', 5)->default('en')->after('explanation');
            $table->index('locale');
        });
    }

    public function down(): void
    {
        Schema::table('trivia_questions', function (Blueprint $table) {
            $table->dropIndex(['locale']);
            $table->dropColumn('locale');
        });
    }
};
