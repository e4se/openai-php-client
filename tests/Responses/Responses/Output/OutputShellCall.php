<?php

use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

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
