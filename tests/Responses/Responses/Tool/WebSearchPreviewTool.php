<?php

use OpenAI\Responses\Responses\Tool\WebSearchPreviewTool;
use OpenAI\Responses\Responses\Tool\WebSearchPreviewUserLocation;

test('preview fields are preserved by the preview schema', function () {
    $attributes = [
        'type' => 'web_search_preview_2025_03_11',
        'search_content_types' => ['text', 'image'],
        'search_context_size' => 'medium',
        'user_location' => [
            'type' => 'approximate',
            'city' => 'San Francisco',
        ],
    ];

    $response = WebSearchPreviewTool::from($attributes);

    expect($response)
        ->toBeInstanceOf(WebSearchPreviewTool::class)
        ->searchContentTypes->toBe(['text', 'image'])
        ->userLocation->toBeInstanceOf(WebSearchPreviewUserLocation::class)
        ->userLocation->type->toBe('approximate');

    expect($response->toArray())->toBe($attributes);
});
