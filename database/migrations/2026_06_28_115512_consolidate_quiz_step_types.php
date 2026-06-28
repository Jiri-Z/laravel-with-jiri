<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->convertType('quiz_single', 'single');
        $this->convertType('quiz_multiple', 'multiple');
        $this->convertType('quiz_text', 'text');
    }

    private function convertType(string $oldType, string $questionType): void
    {
        $rows = DB::table('steps')->where('type', $oldType)->get(['id', 'content']);

        foreach ($rows as $row) {
            $decoded = json_decode((string) $row->content, true);
            if ($decoded === null) {
                continue;
            }
            $decoded['type'] = $questionType;

            DB::table('steps')->where('id', $row->id)->update([
                'type' => 'quiz',
                'content' => json_encode([$decoded]),
            ]);
        }
    }

    public function down(): void
    {
        $this->revertType('single', 'quiz_single');
        $this->revertType('multiple', 'quiz_multiple');
        $this->revertType('text', 'quiz_text');
    }

    private function revertType(string $questionType, string $oldType): void
    {
        $rows = DB::table('steps')
            ->where('type', 'quiz')
            ->where('content', 'like', '%"type":"'.$questionType.'"%')
            ->get(['id', 'content']);

        foreach ($rows as $row) {
            $decoded = json_decode((string) $row->content, true);
            if (! is_array($decoded) || count($decoded) !== 1) {
                continue;
            }
            $inner = $decoded[0];
            unset($inner['type']);

            DB::table('steps')->where('id', $row->id)->update([
                'type' => $oldType,
                'content' => json_encode($inner),
            ]);
        }
    }
};
