<?php

use OpenAI\Responses\Responses\Tool\NamespaceTool;
use OpenAI\Responses\Responses\Tool\NamespaceTools\CustomTool;
use OpenAI\Responses\Responses\Tool\NamespaceTools\FunctionTool;

test('from', function () {
    $response = NamespaceTool::from(toolNamespace());

    expect($response)
        ->toBeInstanceOf(NamespaceTool::class)
        ->description->toBe('A namespace of tools.')
        ->name->toBe('my_namespace')
        ->tools->toBeArray()
        ->type->toBe('namespace');
});

test('as array accessible', function () {
    $response = NamespaceTool::from(toolNamespace());

    expect($response['type'])->toBe('namespace');
});

test('to array', function () {
    $response = NamespaceTool::from(toolNamespace());

    expect($response->toArray())
        ->toBeArray()
        ->toBe(toolNamespace());
});

test('preserves allowed callers', function () {
    $attributes = toolNamespace();
    $customTool = toolCustom();
    $customTool['allowed_callers'] = ['programmatic'];
    $attributes['tools'] = [
        toolFunctionProgrammatic(),
        $customTool,
    ];

    $response = NamespaceTool::from($attributes);

    expect($response->tools)
        ->toHaveCount(2)
        ->{0}->toBeInstanceOf(FunctionTool::class)
        ->{0}->deferLoading->toBeTrue()
        ->{0}->allowedCallers->toBe(['programmatic'])
        ->{0}->outputSchema->toBeArray()
        ->{1}->toBeInstanceOf(CustomTool::class)
        ->{1}->allowedCallers->toBe(['programmatic']);

    expect($response->toArray())
        ->toBe($attributes);
});

test('nested function parameters and strict are optional', function () {
    $attributes = toolNamespace();
    $attributes['tools'] = [
        [
            'name' => 'get_inventory',
            'type' => 'function',
        ],
    ];

    $response = NamespaceTool::from($attributes);

    expect($response->tools)
        ->{0}->toBeInstanceOf(FunctionTool::class)
        ->{0}->parameters->toBeNull()
        ->{0}->strict->toBeNull();

    expect($response->toArray())->toBe($attributes);
});
