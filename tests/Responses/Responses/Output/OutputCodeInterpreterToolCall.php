<?php

use OpenAI\Responses\Responses\Output\CodeInterpreter\CodeImageOutput;
use OpenAI\Responses\Responses\Output\OutputCodeInterpreterToolCall;

test('from', function () {
    $response = OutputCodeInterpreterToolCall::from(outputCodeInterpreterToolCall());

    expect($response)
        ->toBeInstanceOf(OutputCodeInterpreterToolCall::class)
        ->id->toBe('ci_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->status->toBe('completed')
        ->type->toBe('code_interpreter_call')
        ->code->toBe('print("Hello, World!")')
        ->containerId->toBe('container_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->outputs->toBeArray()
        ->outputs->toHaveCount(2);
});

test('as array accessible', function () {
    $response = OutputCodeInterpreterToolCall::from(outputCodeInterpreterToolCall());

    expect($response['id'])->toBe('ci_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c');
});

test('to array', function () {
    $response = OutputCodeInterpreterToolCall::from(outputCodeInterpreterToolCall());

    expect($response->toArray())
        ->toBeArray()
        ->toBe(outputCodeInterpreterToolCall());
});

test('supports nullable code and image outputs', function () {
    $attributes = [
        'code' => null,
        'id' => 'ci_image',
        'outputs' => [
            [
                'type' => 'image',
                'url' => 'https://example.com/chart.png',
            ],
        ],
        'status' => 'completed',
        'type' => 'code_interpreter_call',
        'container_id' => 'container_image',
    ];

    $response = OutputCodeInterpreterToolCall::from($attributes);

    expect($response)
        ->code->toBeNull()
        ->outputs->{0}->toBeInstanceOf(CodeImageOutput::class)
        ->outputs->{0}->url->toBe('https://example.com/chart.png');

    expect($response->toArray())->toBe($attributes);
});

test('preserves empty outputs', function () {
    $attributes = [
        'code' => null,
        'id' => 'ci_empty',
        'outputs' => [],
        'status' => 'completed',
        'type' => 'code_interpreter_call',
        'container_id' => 'container_empty',
    ];

    $response = OutputCodeInterpreterToolCall::from($attributes);

    expect($response->outputs)->toBe([]);
    expect($response->toArray())->toBe($attributes);
});

test('preserves omitted code and outputs', function () {
    $attributes = outputCodeInterpreterToolCall();
    unset($attributes['code'], $attributes['outputs']);

    $response = OutputCodeInterpreterToolCall::from($attributes);

    expect($response)
        ->code->toBeNull()
        ->outputs->toBeNull();
    expect(isset($response['code']))->toBeFalse();
    expect(isset($response['outputs']))->toBeFalse();
    expect($response->toArray())->toBe($attributes);
});
