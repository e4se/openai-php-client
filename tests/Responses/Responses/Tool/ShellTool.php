<?php

use OpenAI\Responses\Responses\Tool\ShellTool;

test('preserves programmatic configuration', function () {
    $attributes = toolShellProgrammatic();
    $tool = ShellTool::from($attributes);

    expect($tool)
        ->toBeInstanceOf(ShellTool::class)
        ->type->toBe('shell')
        ->allowedCallers->toBe(['programmatic'])
        ->environment->toBe($attributes['environment']);

    expect($tool->toArray())->toBe($attributes);
});
