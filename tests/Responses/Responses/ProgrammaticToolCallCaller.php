<?php

use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

test('from', function () {
    $caller = ProgrammaticToolCallCaller::from(programmaticToolCallCaller());

    expect($caller)
        ->toBeInstanceOf(ProgrammaticToolCallCaller::class)
        ->type->toBe('program')
        ->callerId->toBe('call_prog_123');
});

test('as array accessible', function () {
    $caller = ProgrammaticToolCallCaller::from(programmaticToolCallCaller());

    expect($caller['caller_id'])->toBe('call_prog_123');
});

test('to array', function () {
    $caller = ProgrammaticToolCallCaller::from(programmaticToolCallCaller());

    expect($caller->toArray())->toBe(programmaticToolCallCaller());
});
