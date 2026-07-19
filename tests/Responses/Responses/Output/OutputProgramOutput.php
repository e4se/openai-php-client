<?php

use OpenAI\Responses\Responses\Output\OutputProgramOutput;

test('from', function () {
    $output = OutputProgramOutput::from(outputProgramOutput());

    expect($output)
        ->toBeInstanceOf(OutputProgramOutput::class)
        ->type->toBe('program_output')
        ->id->toBe('prog_out_123')
        ->callId->toBe('call_prog_123')
        ->result->toContain('available_units')
        ->status->toBe('completed');
});

test('as array accessible', function () {
    $output = OutputProgramOutput::from(outputProgramOutput());

    expect($output['result'])->toContain('shortage_units');
});

test('to array', function () {
    $output = OutputProgramOutput::from(outputProgramOutput());

    expect($output->toArray())->toBe(outputProgramOutput());
});
