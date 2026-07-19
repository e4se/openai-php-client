<?php

use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('hydrates documented shell call without id', function () {
    $attributes = outputShellCall();
    $call = OutputShellCall::from($attributes);

    expect($call)
        ->toBeInstanceOf(OutputShellCall::class)
        ->callId->toBe('call_shell_documented')
        ->id->toBeNull()
        ->environment->toBeNull();

    expect(isset($call['id']))->toBeFalse();
    expect(isset($call['environment']))->toBeFalse();
    expect($call->toArray())->toBe($attributes);
});

test('preserves programmatic caller', function () {
    $attributes = outputShellCallProgrammatic();
    $call = OutputShellCall::from($attributes);

    expect($call)
        ->toBeInstanceOf(OutputShellCall::class)
        ->callId->toBe('call_shell_123')
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->caller->callerId->toBe('call_prog_123');

    expect($call->toArray())->toBe($attributes);
});

test('preserves explicit null id', function () {
    $attributes = outputShellCallProgrammatic();
    $attributes['id'] = null;

    $call = OutputShellCall::from($attributes);

    expect($call->id)->toBeNull();
    expect(isset($call['id']))->toBeTrue();
    expect($call->toArray())->toBe($attributes);
});

test('preserves required null environment', function () {
    $attributes = outputShellCallProgrammatic();
    $attributes['environment'] = null;

    $call = OutputShellCall::from($attributes);

    expect($call)
        ->environment->toBeNull()
        ->toArray()->toBe($attributes);
});
