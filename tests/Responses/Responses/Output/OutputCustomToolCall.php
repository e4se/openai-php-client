<?php

use OpenAI\Responses\Responses\Output\OutputCustomToolCall;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

it('can parse custom tool call output', function () {
    $response = OutputCustomToolCall::from(outputCustomToolCall());

    expect($response)
        ->toBeInstanceOf(OutputCustomToolCall::class)
        ->type->toBe('custom_tool_call')
        ->callId->toBe('call_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->input->toBe('ls -l')
        ->name->toBe('my_custom_tool')
        ->id->toBe('ct_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c');
});

it('preserves programmatic caller linkage', function () {
    $attributes = outputCustomToolCallProgrammatic();
    $response = OutputCustomToolCall::from($attributes);

    expect($response->caller)
        ->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->callerId->toBe('call_prog_123');

    expect($response->namespace)->toBe('inventory');

    expect($response->toArray())->toBe($attributes);
});

it('accepts response output items without an id', function () {
    $attributes = outputCustomToolCallProgrammatic();
    unset($attributes['id']);

    $response = OutputCustomToolCall::from($attributes);

    expect($response->id)->toBeNull();
    expect($response->toArray())->toBe($attributes);
});
