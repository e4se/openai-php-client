<?php

use OpenAI\Responses\Responses\Output\OutputShellCallOutput;

test('preserves programmatic caller and output', function () {
    $attributes = outputShellCallOutputProgrammatic();
    $output = OutputShellCallOutput::from($attributes);

    expect($output)
        ->toBeInstanceOf(OutputShellCallOutput::class)
        ->callId->toBe('call_shell_123')
        ->output->toBe($attributes['output'])
        ->maxOutputLength->toBe(4096)
        ->caller->callerId->toBe('call_prog_123');

    expect($output->toArray())->toBe($attributes);
});

test('preserves required null max output length', function () {
    $attributes = outputShellCallOutputProgrammatic();
    $attributes['max_output_length'] = null;

    $output = OutputShellCallOutput::from($attributes);

    expect($output)
        ->maxOutputLength->toBeNull()
        ->toArray()->toBe($attributes);
});
