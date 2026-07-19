<?php

use OpenAI\Responses\Responses\Input\AcknowledgedSafetyCheck;
use OpenAI\Responses\Responses\Input\ComputerToolCallOutput;
use OpenAI\Responses\Responses\Input\ComputerToolCallOutputScreenshot;

test('from', function () {
    $attributes = computerToolCallOutputItem();
    $response = ComputerToolCallOutput::from($attributes);

    expect($response)
        ->toBeInstanceOf(ComputerToolCallOutput::class)
        ->callId->toBe('call_computer_123')
        ->id->toBe('computer_output_123')
        ->output->toBeInstanceOf(ComputerToolCallOutputScreenshot::class)
        ->output->imageUrl->toBe('https://example.com/screenshot.png')
        ->type->toBe('computer_call_output')
        ->status->toBe('completed')
        ->acknowledgedSafetyChecks->toHaveCount(1)
        ->acknowledgedSafetyChecks->{0}->toBeInstanceOf(AcknowledgedSafetyCheck::class)
        ->createdBy->toBe('actor_computer');

    expect($response->toArray())->toBe($attributes);
});

test('optional screenshot and safety-check fields may be absent', function () {
    $attributes = [
        'call_id' => 'call_computer_456',
        'id' => 'computer_output_456',
        'output' => ['type' => 'computer_screenshot'],
        'type' => 'computer_call_output',
        'status' => 'failed',
    ];

    set_error_handler(static fn (int $errno, string $errstr): bool => throw new ErrorException($errstr), E_WARNING);

    try {
        $response = ComputerToolCallOutput::from($attributes);
    } finally {
        restore_error_handler();
    }

    expect($response)
        ->status->toBe('failed')
        ->acknowledgedSafetyChecks->toBeNull()
        ->createdBy->toBeNull()
        ->output->fileId->toBeNull()
        ->output->imageUrl->toBeNull();

    expect($response->toArray())->toBe($attributes);
});
