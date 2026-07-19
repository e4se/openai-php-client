<?php

use OpenAI\Responses\Responses\DirectToolCallCaller;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('from with programmatic caller and no status', function () {
    $call = OutputFunctionToolCall::from(outputFunctionToolCallProgrammatic());

    expect($call)
        ->toBeInstanceOf(OutputFunctionToolCall::class)
        ->status->toBeNull()
        ->namespace->toBe('inventory')
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->caller->callerId->toBe('call_prog_123');
});

test('to array with programmatic caller', function () {
    $call = OutputFunctionToolCall::from(outputFunctionToolCallProgrammatic());

    expect($call->toArray())->toBe(outputFunctionToolCallProgrammatic());
});

test('caller remains optional for direct calls', function () {
    $attributes = outputFunctionToolCallProgrammatic();
    unset($attributes['caller']);
    $attributes['status'] = 'completed';

    $call = OutputFunctionToolCall::from($attributes);

    expect($call)
        ->status->toBe('completed')
        ->caller->toBeNull();

    expect($call->toArray())->toBe($attributes);
});

test('from with explicit direct caller', function () {
    $attributes = outputFunctionToolCallProgrammatic();
    $attributes['caller'] = directToolCallCaller();

    $call = OutputFunctionToolCall::from($attributes);

    expect($call->caller)
        ->toBeInstanceOf(DirectToolCallCaller::class)
        ->type->toBe('direct');

    expect($call->toArray())->toBe($attributes);
});

test('id is optional in response output items', function () {
    $attributes = outputFunctionToolCallProgrammatic();
    unset($attributes['id']);

    $call = OutputFunctionToolCall::from($attributes);

    expect($call->id)->toBeNull();
    expect($call->toArray())->toBe($attributes);
});
