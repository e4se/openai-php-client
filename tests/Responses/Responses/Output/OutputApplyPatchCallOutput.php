<?php

use OpenAI\Responses\Responses\Output\OutputApplyPatchCallOutput;

test('preserves programmatic caller and output', function () {
    $attributes = outputApplyPatchCallOutputProgrammatic();
    $output = OutputApplyPatchCallOutput::from($attributes);

    expect($output)
        ->toBeInstanceOf(OutputApplyPatchCallOutput::class)
        ->callId->toBe('call_patch_123')
        ->status->toBe('completed')
        ->caller->callerId->toBe('call_prog_123');

    expect($output->toArray())->toBe($attributes);
});
