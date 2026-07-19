<?php

use OpenAI\Responses\Responses\Output\OutputCustomToolCallOutput;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('from with content array and response metadata', function () {
    $attributes = customToolCallOutputProgrammatic();
    $attributes['output'] = [
        ['type' => 'input_text', 'text' => 'result'],
        ['type' => 'input_image', 'detail' => 'auto', 'file_id' => 'file_123', 'image_url' => null],
    ];
    $attributes['created_by'] = 'actor_custom';

    $output = OutputCustomToolCallOutput::from($attributes);

    expect($output)
        ->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->output->toBe($attributes['output'])
        ->status->toBe('completed')
        ->createdBy->toBe('actor_custom')
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->caller->callerId->toBe('call_prog_123');

    expect($output->toArray())->toBe($attributes);
});
