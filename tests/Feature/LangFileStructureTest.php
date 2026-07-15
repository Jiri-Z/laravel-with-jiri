<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class LangFileStructureTest extends TestCase
{
    private string $enDir;

    private string $csDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->enDir = lang_path('en');
        $this->csDir = lang_path('cs');
    }

    /**
     * @test
     */
    public function all_english_lang_files_exist_in_czech(): void
    {
        $enFiles = collect(scandir($this->enDir))
            ->filter(fn (string $f): bool => str_ends_with($f, '.php'))
            ->values();

        foreach ($enFiles as $file) {
            $this->assertFileExists(
                "{$this->csDir}/{$file}",
                "Missing Czech lang file: {$file}"
            );
        }
    }

    /**
     * @test
     */
    public function root_lang_file_exists_for_both_locales(): void
    {
        $this->assertFileExists(lang_path('en.php'));
        $this->assertFileExists(lang_path('cs.php'));
    }

    /**
     * @test
     */
    public function czech_lang_files_have_same_keys_as_english(): void
    {
        $enFiles = collect(scandir($this->enDir))
            ->filter(fn (string $f): bool => str_ends_with($f, '.php'))
            ->values();

        foreach ($enFiles as $file) {
            $enKeys = array_keys(require "{$this->enDir}/{$file}");
            $csKeys = array_keys(require "{$this->csDir}/{$file}");

            $missing = array_diff($enKeys, $csKeys);
            $extra = array_diff($csKeys, $enKeys);

            $this->assertEmpty(
                $missing,
                "Czech {$file} missing keys: " . implode(', ', $missing)
            );
            $this->assertEmpty(
                $extra,
                "Czech {$file} has extra keys: " . implode(', ', $extra)
            );
        }
    }

    /**
     * @test
     */
    public function factories_lang_has_all_required_keys(): void
    {
        $required = [
            'course_description',
            'lesson_description',
            'step_reading_default',
            'step_reading_content',
            'quiz_single_question',
            'quiz_single_options',
            'quiz_multiple_question',
            'quiz_multiple_options',
            'quiz_text_question',
            'quiz_text_answer',
        ];

        $enKeys = array_keys(require lang_path('en/factories.php'));
        $csKeys = array_keys(require lang_path('cs/factories.php'));

        foreach ($required as $key) {
            $this->assertContains($key, $enKeys, "Missing key in en/factories.php: {$key}");
            $this->assertContains($key, $csKeys, "Missing key in cs/factories.php: {$key}");
        }
    }
}
