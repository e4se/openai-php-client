<?php

use OpenAI\Responses\Responses\Tool\ApplyPatchTool;

test('preserves programmatic callers', function () {
    $attributes = toolApplyPatchProgrammatic();
    $tool = ApplyPatchTool::from($attributes);

    expect($tool)
        ->toBeInstanceOf(ApplyPatchTool::class)
        ->type->toBe('apply_patch')
        ->allowedCallers->toBe(['programmatic']);

    expect($tool->toArray())->toBe($attributes);
});
