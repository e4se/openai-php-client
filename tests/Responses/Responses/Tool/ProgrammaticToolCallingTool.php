<?php

use OpenAI\Responses\Responses\Tool\ProgrammaticToolCallingTool;

test('from', function () {
    $tool = ProgrammaticToolCallingTool::from(toolProgrammaticToolCalling());

    expect($tool)
        ->toBeInstanceOf(ProgrammaticToolCallingTool::class)
        ->type->toBe('programmatic_tool_calling');
});

test('as array accessible', function () {
    $tool = ProgrammaticToolCallingTool::from(toolProgrammaticToolCalling());

    expect($tool['type'])->toBe('programmatic_tool_calling');
});

test('to array', function () {
    $tool = ProgrammaticToolCallingTool::from(toolProgrammaticToolCalling());

    expect($tool->toArray())->toBe(toolProgrammaticToolCalling());
});
