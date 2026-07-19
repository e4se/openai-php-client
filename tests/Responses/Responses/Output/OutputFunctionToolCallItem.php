<?php

use OpenAI\Responses\Responses\Output\OutputFunctionToolCallItem;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('retrieved function item requires id and status', function () {
    $attributes = outputFunctionToolCallProgrammatic();
    $attributes['status'] = 'completed';
    $attributes['created_by'] = 'actor_function';

    $item = OutputFunctionToolCallItem::from($attributes);

    expect($item)
        ->toBeInstanceOf(OutputFunctionToolCallItem::class)
        ->id->toBe('fc_123')
        ->status->toBe('completed')
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->createdBy->toBe('actor_function');

    expect($item->toArray())->toBe($attributes);
});
