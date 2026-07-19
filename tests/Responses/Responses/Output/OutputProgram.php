<?php

use OpenAI\Responses\Responses\Output\OutputProgram;

test('from', function () {
    $program = OutputProgram::from(outputProgram());

    expect($program)
        ->toBeInstanceOf(OutputProgram::class)
        ->type->toBe('program')
        ->id->toBe('prog_123')
        ->callId->toBe('call_prog_123')
        ->code->toContain('Promise.all')
        ->fingerprint->toBe('opaque_replay_state');
});

test('as array accessible', function () {
    $program = OutputProgram::from(outputProgram());

    expect($program['call_id'])->toBe('call_prog_123');
});

test('to array', function () {
    $program = OutputProgram::from(outputProgram());

    expect($program->toArray())->toBe(outputProgram());
});
