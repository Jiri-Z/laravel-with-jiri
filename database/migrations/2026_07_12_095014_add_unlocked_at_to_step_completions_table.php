<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('step_completions', function (Blueprint $table) {
            $table->timestamp('unlocked_at')->nullable();
        });

        DB::table('step_completions')->update(['unlocked_at' => DB::raw('completed_at')]);
    }

    public function down(): void
    {
        Schema::table('step_completions', function (Blueprint $table) {
            $table->dropColumn('unlocked_at');
        });
    }
};
