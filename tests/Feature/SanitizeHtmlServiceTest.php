<?php

declare(strict_types=1);

use App\Actions\ImportLessonFromYaml;
use App\Models\Course;
use App\Models\User;
use App\Services\SanitizeHtmlService;
use Tests\TestCase;

uses(TestCase::class);

test('strips script tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Hello</p><script>alert("xss")</script><p>World</p>');

    expect($result)->toContain('<p>Hello</p>')
        ->toContain('<p>World</p>')
        ->not->toContain('<script>');
});

test('strips iframe tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Content</p><iframe src="http://evil.com"></iframe>');

    expect($result)->toContain('<p>Content</p>')
        ->not->toContain('<iframe');
});

test('strips object tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Text</p><object data="evil.swf"></object>');

    expect($result)->toContain('<p>Text</p>')
        ->not->toContain('<object');
});

test('strips embed tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Text</p><embed src="evil.swf">');

    expect($result)->toContain('<p>Text</p>')
        ->not->toContain('<embed');
});

test('strips form tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Text</p><form action="http://evil.com"><input type="submit"></form>');

    expect($result)->toContain('<p>Text</p>')
        ->not->toContain('<form')
        ->not->toContain('<input');
});

test('strips link tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Text</p><link rel="stylesheet" href="evil.css">');

    expect($result)->toContain('<p>Text</p>')
        ->not->toContain('<link');
});

test('strips style tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Text</p><style>body{background:red}</style>');

    expect($result)->toContain('<p>Text</p>')
        ->not->toContain('<style>');
});

test('strips svg tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p>Text</p><svg onload="alert(1)"><circle r="10"/></svg>');

    expect($result)->toContain('<p>Text</p>')
        ->not->toContain('<svg');
});

test('strips img with onerror', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<img src="x" onerror="alert(1)">');

    expect($result)->not->toContain('onerror')
        ->not->toContain('alert');
});

test('strips javascript uris', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<a href="javascript:alert(1)">click</a>');

    expect($result)->not->toContain('javascript:');
});

test('strips data uris in href', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<a href="data:text/html,<script>alert(1)</script>">click</a>');

    expect($result)->not->toContain('data:');
});

test('allows safe html tags', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<h1>Title</h1><p><strong>Bold</strong> and <em>italic</em></p><ul><li>Item</li></ul>');

    expect($result)->toContain('<h1>Title</h1>')
        ->toContain('<strong>Bold</strong>')
        ->toContain('<em>italic</em>')
        ->toContain('<li>Item</li>');
});

test('allows safe attributes like class and id', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<p class="text-lg" id="intro">Hello</p>');

    expect($result)->toContain('class="text-lg"')
        ->toContain('id="intro"');
});

test('strips event handler attributes', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<div onclick="alert(1)">Click</div>');

    expect($result)->not->toContain('onclick');
});

test('preserves code blocks', function () {
    $service = app(SanitizeHtmlService::class);
    $result = $service->clean('<pre><code>$name = "John";</code></pre>');

    expect($result)->toContain('<pre>')
        ->toContain('<code>')
        ->toContain('$name = "John";');
});

test('import action uses purifier for sanitization', function () {
    $admin = User::factory()->admin()->create();
    $course = Course::factory()->create(['user_id' => $admin->id]);

    $yaml = <<<'YAML'
lesson:
  title: "Purifier Test"
steps:
  - title: "XSS Step"
    type: reading
    content: "<p>Safe</p><script>alert('xss')</script><form action='evil'><input></form><link rel='stylesheet' href='evil.css'><style>body{background:red}</style><svg onload='alert(1)'><img src='x' onerror='alert(1)'><a href='javascript:alert(1)'>click</a><a href='data:text/html,<script>alert(1)</script>'>data</a>"
YAML;

    $action = app(ImportLessonFromYaml::class);
    $result = $action->handle($admin, $yaml, $course);

    $content = $result->steps[0]->reading_content;
    expect($content)->toContain('<p>Safe</p>')
        ->not->toContain('<script>')
        ->not->toContain('<form')
        ->not->toContain('<link')
        ->not->toContain('<style>')
        ->not->toContain('<svg')
        ->not->toContain('onerror')
        ->not->toContain('javascript:')
        ->not->toContain('data:');
});
