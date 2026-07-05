<?php

dataset('quiz_answer_formats', [
    'single answer' => [[
        'type' => 'single',
        'question' => 'What is 2+2?',
        'options' => ['3', '4', '5', '6'],
        'answer' => 1,
        'userAnswer' => 1,
        'explanation' => '2+2 equals 4',
        'difficulty' => 'easy',
        'topic' => 'math',
    ]],
    'multiple answer' => [[
        'type' => 'multiple',
        'question' => 'Which are even numbers?',
        'options' => ['1', '2', '3', '4'],
        'answer' => [1, 3],
        'userAnswer' => [1, 3],
        'explanation' => '2 and 4 are even',
        'difficulty' => 'easy',
        'topic' => 'math',
    ]],
    'text answer' => [[
        'type' => 'text',
        'question' => 'What is the capital of France?',
        'answer' => 'Paris',
        'alternatives' => ['paris'],
        'userAnswer' => 'Paris',
        'explanation' => 'Paris is the capital',
        'difficulty' => 'easy',
        'topic' => 'geography',
    ]],
    'text alternative answer' => [[
        'type' => 'text',
        'question' => 'Who wrote Romeo and Juliet?',
        'answer' => 'William Shakespeare',
        'alternatives' => ['Shakespeare', 'W. Shakespeare'],
        'userAnswer' => 'Shakespeare',
        'explanation' => 'Shakespeare wrote many famous plays',
        'difficulty' => 'medium',
        'topic' => 'literature',
    ]],
]);
