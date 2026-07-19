<?php

use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionClick;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionKeyPress;
use OpenAI\Responses\Responses\Output\OutputComputerToolCall;

test('from', function () {
    $response = OutputComputerToolCall::from(outputComputerToolCall());

    expect($response)
        ->toBeInstanceOf(OutputComputerToolCall::class)
        ->action->toBeInstanceOf(OutputComputerActionClick::class)
        ->actions->toBeNull()
        ->callId->toBe('call_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->id->toBe('cu_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->status->toBe('completed')
        ->pendingSafetyChecks->toBeArray();
});

test('as array accessible', function () {
    $response = OutputComputerToolCall::from(outputComputerToolCall());

    expect($response['id'])->toBe('cu_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c');
});

test('to array', function () {
    $response = OutputComputerToolCall::from(outputComputerToolCall());

    expect($response->toArray())
        ->toBeArray()
        ->toBe(outputComputerToolCall());
});

test('hydrates and serializes GA batched actions', function () {
    $response = OutputComputerToolCall::from(outputComputerToolCallGa());

    expect($response)
        ->toBeInstanceOf(OutputComputerToolCall::class)
        ->action->toBeNull()
        ->actions->toHaveCount(2)
        ->actions->{0}->toBeInstanceOf(OutputComputerActionClick::class)
        ->actions->{1}->toBeInstanceOf(OutputComputerActionKeyPress::class);

    expect($response->toArray())->toBe(outputComputerToolCallGa());
});
