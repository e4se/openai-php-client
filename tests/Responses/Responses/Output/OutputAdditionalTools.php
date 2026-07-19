<?php

use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Tool\ComputerTool;
use OpenAI\Responses\Responses\Tool\FunctionTool;
use OpenAI\Responses\Responses\Tool\WebSearchTool;

test('from', function () {
    $response = OutputAdditionalTools::from(outputAdditionalTools());

    expect($response)
        ->toBeInstanceOf(OutputAdditionalTools::class)
        ->id->toBe('at_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->role->toBe('developer')
        ->tools->toHaveCount(1)
        ->tools->{0}->toBeInstanceOf(FunctionTool::class)
        ->type->toBe('additional_tools');
});

test('to array', function () {
    $response = OutputAdditionalTools::from(outputAdditionalTools());

    expect($response->toArray())->toBe(outputAdditionalTools());
});

test('id is optional for input items', function () {
    $attributes = outputAdditionalTools();
    unset($attributes['id']);

    $response = OutputAdditionalTools::from($attributes);

    expect($response->id)->toBeNull();
    expect($response->toArray())->toBe($attributes);
});

test('current computer and web search tools are preserved', function () {
    $attributes = outputAdditionalTools();
    $attributes['tools'] = [
        ['type' => 'computer'],
        [
            'type' => 'web_search_2025_08_26',
            'filters' => ['allowed_domains' => ['example.com']],
        ],
    ];

    $response = OutputAdditionalTools::from($attributes);

    expect($response->tools)
        ->toHaveCount(2)
        ->{0}->toBeInstanceOf(ComputerTool::class)
        ->{1}->toBeInstanceOf(WebSearchTool::class);

    expect($response->toArray())->toBe($attributes);
});
