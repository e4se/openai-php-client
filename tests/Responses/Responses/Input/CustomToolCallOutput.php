<?php

use OpenAI\Responses\Responses\Input\CustomToolCallOutput;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('from', function () {
    $response = CustomToolCallOutput::from(customToolCallOutputItem());

    expect($response)
        ->toBeInstanceOf(CustomToolCallOutput::class)
        ->type->toBe('custom_tool_call_output')
        ->id->toBe('cto_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->callId->toBe('call_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->output->toBe('custom-output');
});

it('is array accessible', function () {
    $response = CustomToolCallOutput::from(customToolCallOutputItem());

    expect($response['output'])->toBe('custom-output');
});

it('to array', function () {
    $response = CustomToolCallOutput::from(customToolCallOutputItem());

    expect($response->toArray())
        ->toBe(customToolCallOutputItem());
});

it('preserves programmatic caller linkage', function () {
    $attributes = customToolCallOutputProgrammatic();
    $response = CustomToolCallOutput::from($attributes);

    expect($response->caller)
        ->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->callerId->toBe('call_prog_123');

    expect($response->status)->toBe('completed');

    expect($response->toArray())->toBe($attributes);
});

it('preserves content array output', function () {
    $attributes = customToolCallOutputProgrammatic();
    $attributes['output'] = [
        ['type' => 'input_text', 'text' => 'result'],
    ];

    $response = CustomToolCallOutput::from($attributes);

    expect($response->output)->toBe($attributes['output']);
    expect($response->toArray())->toBe($attributes);
});

it('accepts conversation output items without an id', function () {
    $attributes = customToolCallOutputProgrammatic();
    unset($attributes['id']);

    $response = CustomToolCallOutput::from($attributes);

    expect($response->id)->toBeNull();
    expect($response->toArray())->toBe($attributes);
});
