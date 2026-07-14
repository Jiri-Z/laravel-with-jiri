<?php

declare(strict_types=1);

// This file configures PHP-CS-Fixer for Duster.
// The php_unit_test_annotation fixer is disabled because it converts
// test_ prefix methods to @test annotations, which breaks PHPUnit 12
// (PHPUnit 12 removed @test annotation support).

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude(['vendor', 'node_modules', 'storage', 'bootstrap/cache']);

return (new PhpCsFixer\Config)
    ->setFinder($finder)
    ->setRules([
        'php_unit_test_annotation' => false,
    ]);
