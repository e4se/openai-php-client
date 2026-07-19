<?php

use OpenAI\Responses\Responses\Output\OutputCustomToolCallItem;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('retrieved custom item requires id and status', function () {
    $attributes = outputCustomToolCallProgrammatic();
    $attributes['status'] = 'completed';
    $attributes['created_by'] = 'actor_custom';

    $item = OutputCustomToolCallItem::from($attributes);

    expect($item)
        ->toBeInstanceOf(OutputCustomToolCallItem::class)
        ->id->toStartWith('ct_')
        ->status->toBe('completed')
        ->caller->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->createdBy->toBe('actor_custom');

    expect($item->toArray())->toBe($attributes);
});
