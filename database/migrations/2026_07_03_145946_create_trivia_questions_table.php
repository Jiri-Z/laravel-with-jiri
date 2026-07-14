<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trivia_questions', function (Blueprint $table) {
            $table->id();
            $table->string('topic');
            $table->string('type');
            $table->string('difficulty');
            $table->text('question');
            $table->text('options')->nullable();
            $table->string('answer');
            $table->text('alternatives')->nullable();
            $table->text('explanation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trivia_questions');
    }
};
