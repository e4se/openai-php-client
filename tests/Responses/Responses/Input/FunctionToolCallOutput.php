<?php

use OpenAI\Responses\Responses\DirectToolCallCaller;
use OpenAI\Responses\Responses\Input\FunctionToolCallOutput;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('from with programmatic caller', function () {
    $output = FunctionToolCallOutput::from(functionToolCallOutputProgrammatic());

    expect($output)
        ->toBeInstanceOf(FunctionToolCallOutput::class)
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->caller->callerId->toBe('call_prog_123');
});

test('to array with programmatic caller', function () {
    $output = FunctionToolCallOutput::from(functionToolCallOutputProgrammatic());

    expect($output->toArray())->toBe(functionToolCallOutputProgrammatic());
});

test('caller remains optional for direct call outputs', function () {
    $attributes = functionToolCallOutputProgrammatic();
    unset($attributes['caller']);

    $output = FunctionToolCallOutput::from($attributes);

    expect($output->caller)->toBeNull();
    expect($output->toArray())->toBe($attributes);
});

test('from with explicit direct caller', function () {
    $attributes = functionToolCallOutputProgrammatic();
    $attributes['caller'] = directToolCallCaller();

    $output = FunctionToolCallOutput::from($attributes);

    expect($output->caller)
        ->toBeInstanceOf(DirectToolCallCaller::class)
        ->type->toBe('direct');

    expect($output->toArray())->toBe($attributes);
});

test('content array output', function () {
    $attributes = functionToolCallOutputProgrammatic();
    $attributes['output'] = [
        ['type' => 'input_text', 'text' => 'result'],
    ];

    $output = FunctionToolCallOutput::from($attributes);

    expect($output->output)->toBe($attributes['output']);
    expect($output->toArray())->toBe($attributes);
});

test('response-only id and status remain optional in input params', function () {
    $attributes = functionToolCallOutputProgrammatic();
    unset($attributes['id'], $attributes['status']);

    $output = FunctionToolCallOutput::from($attributes);

    expect($output)
        ->id->toBeNull()
        ->status->toBeNull();

    expect($output->toArray())->toBe($attributes);
});
