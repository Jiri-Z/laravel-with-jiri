<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\TriviaQuiz;
use App\Models\TriviaAttempt;
use App\Models\TriviaQuestion;
use App\Models\User;
use Database\Seeders\TriviaQuestionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

function triviaQuestion(array $overrides = []): array
{
    return array_merge([
        'topic' => 'routing',
        'type' => 'single',
        'difficulty' => 'easy',
        'question' => 'Default question text?',
        'options' => null,
        'answer' => 'default answer',
        'alternatives' => null,
        'explanation' => 'Default explanation.',
        'locale' => 'en',
        'created_at' => '2026-01-01 00:00:00',
        'updated_at' => '2026-01-01 00:00:00',
    ], $overrides);
}

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'student']);
    TriviaQuestion::insert([
        triviaQuestion([
            'topic' => 'routing',
            'question' => 'What does Route::get() do?',
            'options' => json_encode(['Registers a GET route', 'Registers a POST route']),
            'answer' => 'Registers a GET route',
            'explanation' => 'Route::get registers a route that responds to GET requests.',
        ]),
        triviaQuestion([
            'topic' => 'blade-templates',
            'question' => 'What does {{ }} do in Blade?',
            'options' => json_encode(['Escapes output', 'Raw output']),
            'answer' => 'Escapes output',
            'explanation' => '{{ }} escapes HTML.',
        ]),
        triviaQuestion([
            'topic' => 'routing',
            'type' => 'text',
            'difficulty' => 'medium',
            'question' => 'What artisan command lists all routes?',
            'options' => null,
            'answer' => 'route:list',
            'alternatives' => json_encode(['php artisan route:list']),
            'explanation' => 'route:list shows all registered routes.',
        ]),
        triviaQuestion([
            'topic' => 'blade-templates',
            'type' => 'multiple',
            'difficulty' => 'hard',
            'question' => 'Which are valid Blade directives?',
            'options' => json_encode(['@auth', '@guest', '@login']),
            'answer' => json_encode(['@auth', '@guest']),
            'alternatives' => null,
            'explanation' => '@auth and @guest check auth status.',
        ]),
    ]);
});

test('guest is redirected to login', function () {
    $this->get('/quiz')->assertRedirect('/login');
});

test('quiz page loads for authenticated user', function () {
    $this->actingAs($this->user)
        ->get('/quiz')
        ->assertOk()
        ->assertSeeLivewire(TriviaQuiz::class);
});

test('welcome screen shows topics', function () {
    Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->assertOk()
        ->assertSee('Laravel Trivia')
        ->assertSee('Routing')
        ->assertSee('Blade Templates');
});

test('can start quiz with selected topics', function () {
    Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->set('selectedTopics', ['routing', 'blade-templates'])
        ->call('start')
        ->assertSet('screen', 'quiz')
        ->assertCount('questions', 4);
});

test('cannot start quiz with no topics selected', function () {
    Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->set('selectedTopics', [])
        ->call('start')
        ->assertSet('screen', 'welcome');
});

test('can submit single answer and see feedback', function () {
    Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->set('selectedTopics', ['routing'])
        ->call('start')
        ->assertSet('screen', 'quiz')
        ->set('userAnswers.0', 'Registers a GET route')
        ->call('submit')
        ->assertSet('submitted', true);
});

test('can navigate through all questions and reach results', function () {
    $component = Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->set('selectedTopics', ['routing', 'blade-templates'])
        ->call('start');

    $questions = $component->get('questions');
    $count = count($questions);

    foreach (range(0, $count - 2) as $i) {
        $component->set("userAnswers.{$i}", 'test');
        $component->call('submit');
        $component->call('nextQuestion');
    }

    $lastIdx = $count - 1;
    $component->set("userAnswers.{$lastIdx}", 'test');
    $component->call('submit');
    $component->call('nextQuestion');

    $component->assertSet('screen', 'results');
});

test('results screen saves attempt and shows score', function () {
    $component = Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->set('selectedTopics', ['routing'])
        ->call('start');

    $questions = $component->get('questions');
    $count = count($questions);

    foreach (range(0, $count - 1) as $i) {
        $component->set("userAnswers.{$i}", 'test');
        $component->call('submit');
        $component->call('nextQuestion');
    }

    $component->assertSet('screen', 'results');
    $component->assertSet('attemptId', fn ($id) => $id !== null);

    $attempt = TriviaAttempt::find($component->get('attemptId'));
    expect($attempt)->not->toBeNull();
    expect($attempt->user_id)->toBe($this->user->id);
    expect($attempt->total)->toBe($count);
});

test('topic question counts are filtered by locale', function () {
    TriviaQuestion::insert(triviaQuestion([
        'topic' => 'routing',
        'question' => 'CS: routing question?',
        'locale' => 'cs',
    ]));

    $component = Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class);

    $counts = $component->get('topicQuestionCounts');
    $routing = $counts->firstWhere('topic', 'routing');

    expect($routing)->not->toBeNull();
    expect($routing['count'])->toBe(2); // only en questions
});

test('trivia questions are filtered by locale', function () {
    // Additional CS question for same topic
    TriviaQuestion::insert(triviaQuestion([
        'topic' => 'routing',
        'question' => 'CS: routing question?',
        'locale' => 'cs',
    ]));

    // Component should only show English questions
    Livewire::actingAs($this->user)
        ->test(TriviaQuiz::class)
        ->assertCount('allTopics', 2) // routing, blade-templates
        ->set('selectedTopics', ['routing'])
        ->call('start')
        ->assertCount('questions', 2); // 2 en routing questions, not 3
});

test('trivia question seeder sets correct locale', function () {
    $this->seed(TriviaQuestionSeeder::class);

    $enCount = TriviaQuestion::where('locale', 'en')->count();
    expect($enCount)->toBeGreaterThan(0);

    $csCount = TriviaQuestion::where('locale', 'cs')->count();
    expect($csCount)->toBe(0); // No CS YAML files exist yet
});

test('dashboard shows trivia card', function () {
    $this->actingAs($this->user)
        ->get('/dashboard')
        ->assertOk()
        ->assertSee('Laravel Trivia')
        ->assertSee('Test Your Knowledge');
});
