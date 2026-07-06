<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->text('content')->nullable()->change();
            $table->text('reading_content')->nullable()->after('content');
            $table->text('quiz_content')->nullable()->after('reading_content');
            $table->text('coding_content')->nullable()->after('quiz_content');
        });

        $steps = DB::table('steps')->get();

        foreach ($steps as $step) {
            $updates = ['reading_content' => null, 'quiz_content' => null, 'coding_content' => null];

            match ($step->type) {
                'reading' => $updates['reading_content'] = $step->content,
                'quiz' => $updates['quiz_content'] = $step->content,
                'coding' => $updates['coding_content'] = $step->content,
                default => null,
            };

            DB::table('steps')->where('id', $step->id)->update($updates);
        }
    }

    public function down(): void
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->dropColumn(['reading_content', 'quiz_content', 'coding_content']);
        });
    }
};
