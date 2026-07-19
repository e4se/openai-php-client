<?php

use OpenAI\Responses\Responses\Tool\FunctionTool;

test('from with programmatic configuration', function () {
    $tool = FunctionTool::from(toolFunctionProgrammatic());

    expect($tool)
        ->toBeInstanceOf(FunctionTool::class)
        ->name->toBe('get_inventory')
        ->type->toBe('function')
        ->deferLoading->toBeTrue()
        ->allowedCallers->toBe(['programmatic'])
        ->outputSchema->toBeArray();
});

test('to array with programmatic configuration', function () {
    $tool = FunctionTool::from(toolFunctionProgrammatic());

    expect($tool->toArray())->toBe(toolFunctionProgrammatic());
});

test('programmatic configuration is optional', function () {
    $attributes = toolFunctionProgrammatic();
    unset($attributes['defer_loading'], $attributes['allowed_callers'], $attributes['output_schema']);

    $tool = FunctionTool::from($attributes);

    expect($tool)
        ->allowedCallers->toBeNull()
        ->deferLoading->toBeNull()
        ->outputSchema->toBeNull();

    expect($tool->toArray())->toBe($attributes);
});

test('parameters and strict are serialized when null', function () {
    $attributes = [
        'name' => 'get_inventory',
        'parameters' => null,
        'strict' => null,
        'type' => 'function',
    ];

    $tool = FunctionTool::from($attributes);

    expect($tool)
        ->parameters->toBeNull()
        ->strict->toBeNull();

    expect($tool->toArray())->toBe($attributes);
});
