<?php

use OpenAI\Responses\Responses\Tool\LocalShellTool;

test('from', function () {
    $attributes = toolLocalShell();
    $tool = LocalShellTool::from($attributes);

    expect($tool)
        ->toBeInstanceOf(LocalShellTool::class)
        ->type->toBe('local_shell');

    expect($tool->toArray())->toBe($attributes);
});

test('preserves allowed callers', function () {
    $attributes = [
        'type' => 'local_shell',
        'allowed_callers' => ['direct', 'programmatic'],
    ];

    $tool = LocalShellTool::from($attributes);

    expect($tool->allowedCallers)->toBe(['direct', 'programmatic']);
    expect($tool->toArray())->toBe($attributes);
});
