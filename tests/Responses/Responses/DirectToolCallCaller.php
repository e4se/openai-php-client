<?php

use OpenAI\Responses\Responses\DirectToolCallCaller;

test('from', function () {
    $caller = DirectToolCallCaller::from(directToolCallCaller());

    expect($caller)
        ->toBeInstanceOf(DirectToolCallCaller::class)
        ->type->toBe('direct');
});

test('to array', function () {
    $caller = DirectToolCallCaller::from(directToolCallCaller());

    expect($caller->toArray())
        ->toBe(directToolCallCaller());
});
