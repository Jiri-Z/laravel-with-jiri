<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\AnswerChecker;
use Tests\TestCase;

class AnswerCheckerTest extends TestCase
{
    private AnswerChecker $checker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->checker = new AnswerChecker;
    }

    public function test_single_correct_integer_answer(): void
    {
        expect($this->checker->checkSingle(2, 2))->toBeTrue();
    }

    public function test_single_incorrect_integer_answer(): void
    {
        expect($this->checker->checkSingle(1, 2))->toBeFalse();
    }

    public function test_single_correct_numeric_string_answer(): void
    {
        expect($this->checker->checkSingle('2', 2))->toBeTrue();
    }

    public function test_multiple_matching_sets(): void
    {
        expect($this->checker->checkMultiple([1, 3], [3, 1]))->toBeTrue();
    }

    public function test_multiple_mismatched_sets(): void
    {
        expect($this->checker->checkMultiple([1], [3, 1]))->toBeFalse();
    }

    public function test_multiple_with_duplicates_in_user_answer(): void
    {
        expect($this->checker->checkMultiple([1, 1, 3], [3, 1]))->toBeTrue();
    }

    public function test_text_exact_match(): void
    {
        expect($this->checker->checkText('Paris', 'Paris'))->toBeTrue();
    }

    public function test_text_case_insensitive_match(): void
    {
        expect($this->checker->checkText('paris', 'Paris'))->toBeTrue();
    }

    public function test_text_trimmed_match(): void
    {
        expect($this->checker->checkText('  Paris  ', 'Paris'))->toBeTrue();
    }

    public function test_text_alternative_match(): void
    {
        expect($this->checker->checkText('Shakespeare', 'William Shakespeare', ['Shakespeare']))->toBeTrue();
    }

    public function test_text_no_match(): void
    {
        expect($this->checker->checkText('London', 'Paris'))->toBeFalse();
    }

    public function test_text_alternative_no_match(): void
    {
        expect($this->checker->checkText('London', 'Paris', ['Berlin']))->toBeFalse();
    }

    public function test_check_delegates_single(): void
    {
        expect($this->checker->check('single', 2, ['answer' => 2]))->toBeTrue();
    }

    public function test_check_delegates_multiple(): void
    {
        expect($this->checker->check('multiple', [1, 3], ['answer' => [3, 1]]))->toBeTrue();
    }

    public function test_check_delegates_text(): void
    {
        expect($this->checker->check('text', 'Paris', ['answer' => 'Paris']))->toBeTrue();
    }

    public function test_check_unknown_type_returns_false(): void
    {
        expect($this->checker->check('unknown', 'anything', ['answer' => 'anything']))->toBeFalse();
    }

    public function test_single_with_null_answer_returns_false(): void
    {
        expect($this->checker->checkSingle(null, 2))->toBeFalse();
    }

    public function test_text_with_empty_string_returns_false(): void
    {
        expect($this->checker->checkText('', 'Paris'))->toBeFalse();
    }

    public function test_text_with_whitespace_only_returns_false(): void
    {
        expect($this->checker->checkText('   ', 'Paris'))->toBeFalse();
    }

    public function test_text_utf8_case_insensitive(): void
    {
        expect($this->checker->checkText('PŘÍLIŠ ŽLUŤOUČKÝ KŮŇ', 'příliš žluťoučký kůň'))->toBeTrue();
    }

    public function test_multiple_empty_user_answer(): void
    {
        expect($this->checker->checkMultiple([], [1, 2]))->toBeFalse();
    }

    public function test_multiple_both_empty(): void
    {
        expect($this->checker->checkMultiple([], []))->toBeTrue();
    }

    // --- check() with caller-style content arrays ---

    public function test_check_single_with_correct_answer_fallback(): void
    {
        expect($this->checker->check('single', 2, ['correct_answer' => 2]))->toBeTrue();
    }

    public function test_check_multiple_with_correct_answer_fallback(): void
    {
        expect($this->checker->check('multiple', [1, 3], ['correct_answer' => [3, 1]]))->toBeTrue();
    }

    public function test_check_multiple_with_correct_answers_fallback(): void
    {
        expect($this->checker->check('multiple', [1, 3], ['correct_answers' => [3, 1]]))->toBeTrue();
    }

    public function test_check_text_with_alternatives(): void
    {
        expect($this->checker->check('text', 'Shakespeare', [
            'answer' => 'William Shakespeare',
            'alternatives' => ['Shakespeare'],
        ]))->toBeTrue();
    }

    public function test_check_text_without_alternatives_falls_back_to_empty(): void
    {
        expect($this->checker->check('text', 'Paris', ['answer' => 'Paris']))->toBeTrue();
    }

    public function test_check_null_user_answer_returns_false(): void
    {
        expect($this->checker->check('single', null, ['answer' => 2]))->toBeFalse();
    }

    public function test_check_empty_string_user_answer_returns_false(): void
    {
        expect($this->checker->check('text', '', ['answer' => 'Paris']))->toBeFalse();
    }
}
