<?php

use OpenAI\Responses\Responses\Tool\ComputerTool;

test('from and to array', function () {
    $response = ComputerTool::from(['type' => 'computer']);

    expect($response)
        ->toBeInstanceOf(ComputerTool::class)
        ->type->toBe('computer');

    expect($response->toArray())->toBe(['type' => 'computer']);
});
