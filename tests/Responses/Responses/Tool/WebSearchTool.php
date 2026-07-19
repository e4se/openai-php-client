<?php

use OpenAI\Responses\Responses\Tool\WebSearchTool;
use OpenAI\Responses\Responses\Tool\WebSearchUserLocation;

test('current web search may contain only its type', function () {
    $attributes = ['type' => 'web_search_2025_08_26'];
    $response = WebSearchTool::from($attributes);

    expect($response)
        ->toBeInstanceOf(WebSearchTool::class)
        ->type->toBe('web_search_2025_08_26')
        ->filters->toBeNull()
        ->searchContentTypes->toBeNull()
        ->searchContextSize->toBeNull()
        ->userLocation->toBeNull();

    expect($response->toArray())->toBe($attributes);
});

test('current web search fields are preserved', function () {
    $attributes = [
        'type' => 'web_search',
        'filters' => ['allowed_domains' => ['example.com']],
        'search_context_size' => 'high',
        'user_location' => [
            'city' => 'Dubai',
            'country' => 'AE',
            'timezone' => 'Asia/Dubai',
        ],
    ];
    $response = WebSearchTool::from($attributes);

    expect($response)
        ->filters->toBe(['allowed_domains' => ['example.com']])
        ->searchContextSize->toBe('high')
        ->userLocation->toBeInstanceOf(WebSearchUserLocation::class)
        ->userLocation->type->toBeNull();

    expect($response->toArray())->toBe($attributes);
});

test('preview search content types are preserved', function () {
    $attributes = [
        'type' => 'web_search_preview_2025_03_11',
        'search_content_types' => ['text', 'image'],
    ];
    $response = WebSearchTool::from($attributes);

    expect($response->searchContentTypes)->toBe(['text', 'image']);
    expect($response->toArray())->toBe($attributes);
});
