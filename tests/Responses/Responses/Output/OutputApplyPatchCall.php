<?php

use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;

test('preserves programmatic caller and operation', function () {
    $attributes = outputApplyPatchCallProgrammatic();
    $call = OutputApplyPatchCall::from($attributes);

    expect($call)
        ->toBeInstanceOf(OutputApplyPatchCall::class)
        ->callId->toBe('call_patch_123')
        ->operation->toBe($attributes['operation'])
        ->caller->callerId->toBe('call_prog_123');

    expect($call->toArray())->toBe($attributes);
});
