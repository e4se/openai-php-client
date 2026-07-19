<?php

use OpenAI\Responses\Responses\Output\OutputFunctionToolCallOutput;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('from with content array and response metadata', function () {
    $attributes = functionToolCallOutputProgrammatic();
    $attributes['output'] = [
        ['type' => 'input_text', 'text' => 'result'],
        ['type' => 'input_file', 'file_id' => 'file_123', 'filename' => 'result.txt'],
    ];
    $attributes['created_by'] = 'actor_function';

    $output = OutputFunctionToolCallOutput::from($attributes);

    expect($output)
        ->toBeInstanceOf(OutputFunctionToolCallOutput::class)
        ->output->toBe($attributes['output'])
        ->status->toBe('completed')
        ->createdBy->toBe('actor_function')
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->caller->callerId->toBe('call_prog_123');

    expect($output->toArray())->toBe($attributes);
});
