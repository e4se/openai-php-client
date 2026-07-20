<?php

use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;

test('from', function () {
    $response = OutputFunctionToolCall::from(outputFunctionToolCall());

    expect($response)
        ->toBeInstanceOf(OutputFunctionToolCall::class)
        ->arguments->toBe('{"customer_id":"CUST-12345"}')
        ->callId->toBe('call_abc123')
        ->name->toBe('list_open_orders')
        ->namespace->toBe('crm')
        ->type->toBe('function_call')
        ->id->toBe('fc_abc123')
        ->status->toBe('completed');
});

test('from without namespace', function () {
    $attributes = outputFunctionToolCall();

    unset($attributes['namespace']);

    set_error_handler(static fn (int $errno, string $errstr): bool => throw new ErrorException($errstr), E_WARNING);

    try {
        $response = OutputFunctionToolCall::from($attributes);
    } finally {
        restore_error_handler();
    }

    expect($response->namespace)->toBeNull();
});

test('as array accessible', function () {
    $response = OutputFunctionToolCall::from(outputFunctionToolCall());

    expect($response['namespace'])->toBe('crm');
});

test('to array', function () {
    $response = OutputFunctionToolCall::from(outputFunctionToolCall());

    expect($response->toArray())
        ->toBeArray()
        ->toBe(outputFunctionToolCall());
});
