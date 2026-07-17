<?php

declare(strict_types=1);
use Tests\TestCase;

uses(TestCase::class);

test('telescope storage connection does not hardcode mysql as default', function () {
    $configPath = base_path('config/telescope.php');
    $contents = file_get_contents($configPath);

    expect($contents)->not->toContain("'connection' => env('DB_CONNECTION', 'mysql')");
});

test('telescope storage connection matches app database default', function () {
    $telescopeConnection = config('telescope.storage.database.connection');
    $appConnection = config('database.default');

    expect($telescopeConnection)->toBe($appConnection);
});
