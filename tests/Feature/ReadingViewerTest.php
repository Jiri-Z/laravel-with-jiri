<?php

namespace Tests\Feature;

use App\Livewire\ReadingViewer;
use App\Models\Step;
use Livewire\Livewire;
use Tests\TestCase;

class ReadingViewerTest extends TestCase
{
    public function test_renders_bold_markdown(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'reading_content' => 'This is **bold** text.',
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<strong>bold</strong>');
    }

    public function test_renders_inline_code(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'reading_content' => 'Use the `User::find()` method.',
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<code>User::find()</code>');
    }

    public function test_renders_code_block(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'reading_content' => "```php\necho 'hello';\n```",
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<code class="language-php">');
    }

    public function test_renders_list(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'reading_content' => "- Item 1\n- Item 2\n- Item 3",
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('<li>Item 1</li>')
            ->assertSeeHtml('<li>Item 2</li>')
            ->assertSeeHtml('<li>Item 3</li>');
    }

    public function test_escapes_raw_html(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'reading_content' => '<script>alert(1)</script>',
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertDontSeeHtml('<script>alert(1)</script>');
    }

    public function test_has_prose_class(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'reading_content' => 'Simple content.',
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSeeHtml('class="prose')
            ->assertSeeHtml('prose-invert');
    }

    public function test_aborts_on_non_reading_type(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->quiz()->create([
            'lesson_id' => $lesson->id,
        ]);

        Livewire::actingAs($user)
            ->test(ReadingViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertStatus(404);
    }
}
