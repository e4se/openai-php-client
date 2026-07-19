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
