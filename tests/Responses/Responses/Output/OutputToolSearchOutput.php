<?php

use OpenAI\Responses\Responses\Output\OutputToolSearchOutput;
use OpenAI\Responses\Responses\Tool\FunctionTool;
use OpenAI\Responses\Responses\Tool\RemoteMcpTool;
use OpenAI\Responses\Responses\Tool\WebSearchTool;

test('from', function () {
    $response = OutputToolSearchOutput::from(outputToolSearchOutput());

    expect($response)
        ->toBeInstanceOf(OutputToolSearchOutput::class)
        ->id->toBe('tso_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->callId->toBe('call_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->execution->toBe('server')
        ->status->toBe('completed')
        ->tools->toBeArray()
        ->tools->toHaveCount(1)
        ->tools->{0}->toBeInstanceOf(WebSearchTool::class)
        ->type->toBe('tool_search_output')
        ->createdBy->toBe('user_123');
});

test('as array accessible', function () {
    $response = OutputToolSearchOutput::from(outputToolSearchOutput());

    expect($response['id'])->toBe('tso_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c');
});

test('to array', function () {
    $response = OutputToolSearchOutput::from(outputToolSearchOutput());

    expect($response->toArray())
        ->toBeArray()
        ->toBe(outputToolSearchOutput());
});

test('preserves deferred programmatic tools', function () {
    $attributes = outputToolSearchOutput();
    $attributes['tools'] = [
        toolFunctionProgrammatic(),
        [
            ...toolRemoteMcp(),
            'allowed_callers' => ['programmatic'],
        ],
    ];

    $response = OutputToolSearchOutput::from($attributes);

    expect($response->tools)
        ->{0}->toBeInstanceOf(FunctionTool::class)
        ->{0}->deferLoading->toBeTrue()
        ->{0}->allowedCallers->toBe(['programmatic'])
        ->{1}->toBeInstanceOf(RemoteMcpTool::class)
        ->{1}->deferLoading->toBeTrue()
        ->{1}->allowedCallers->toBe(['programmatic']);

    expect($response->toArray())->toBe($attributes);
});
