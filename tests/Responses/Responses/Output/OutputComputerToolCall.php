<?php

use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionClick;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionDoubleClick;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionDrag;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionKeyPress;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionMove;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionScroll;
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
        ->actions->{1}->toBeInstanceOf(OutputComputerActionKeyPress::class)
        ->id->toBe('cu_ga_computer_123')
        ->pendingSafetyChecks->toBeArray()
        ->pendingSafetyChecks->toBeEmpty();

    expect($response->toArray())->toBe(outputComputerToolCallGa());
});

test('preserves modifier keys on batched mouse actions', function () {
    $attributes = outputComputerToolCallGa();
    $attributes['actions'] = [
        ['button' => 'left', 'type' => 'click', 'x' => 10, 'y' => 20, 'keys' => ['CTRL']],
        ['type' => 'double_click', 'x' => 30.0, 'y' => 40.0, 'keys' => ['SHIFT']],
        ['path' => [['x' => 50, 'y' => 60], ['x' => 70, 'y' => 80]], 'type' => 'drag', 'keys' => ['ALT']],
        ['type' => 'move', 'x' => 90, 'y' => 100, 'keys' => ['META']],
        ['scroll_x' => 0, 'scroll_y' => 120, 'type' => 'scroll', 'x' => 110, 'y' => 120, 'keys' => ['CTRL', 'SHIFT']],
    ];

    $response = OutputComputerToolCall::from($attributes);

    expect($response->actions)
        ->{0}->toBeInstanceOf(OutputComputerActionClick::class)
        ->{0}->keys->toBe(['CTRL'])
        ->{1}->toBeInstanceOf(OutputComputerActionDoubleClick::class)
        ->{1}->keys->toBe(['SHIFT'])
        ->{2}->toBeInstanceOf(OutputComputerActionDrag::class)
        ->{2}->keys->toBe(['ALT'])
        ->{3}->toBeInstanceOf(OutputComputerActionMove::class)
        ->{3}->keys->toBe(['META'])
        ->{4}->toBeInstanceOf(OutputComputerActionScroll::class)
        ->{4}->keys->toBe(['CTRL', 'SHIFT']);

    expect($response->toArray())->toBe($attributes);
});
