<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('step_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('step_id')->constrained()->cascadeOnDelete();
            $table->text('answer');
            $table->boolean('is_correct');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'step_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('step_answers');
    }
};
