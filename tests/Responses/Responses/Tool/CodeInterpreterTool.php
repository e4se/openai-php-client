<?php

use OpenAI\Responses\Responses\Tool\CodeInterpreterTool;

test('preserves allowed callers', function () {
    $attributes = [
        'container' => 'container_123',
        'type' => 'code_interpreter',
        'allowed_callers' => ['programmatic'],
    ];

    $tool = CodeInterpreterTool::from($attributes);

    expect($tool->allowedCallers)
        ->toBe(['programmatic']);

    expect($tool->toArray())
        ->toBe($attributes);
});

test('preserves auto container configuration', function (array $networkPolicy) {
    $attributes = [
        'container' => [
            'file_ids' => ['file_123'],
            'type' => 'auto',
            'memory_limit' => '4g',
            'network_policy' => $networkPolicy,
        ],
        'type' => 'code_interpreter',
        'allowed_callers' => ['programmatic'],
    ];

    $tool = CodeInterpreterTool::from($attributes);

    expect($tool->container)
        ->memoryLimit->toBe('4g')
        ->networkPolicy->toBe($networkPolicy);

    expect($tool->toArray())
        ->toBe($attributes);
})->with([
    'disabled network access' => [[
        'type' => 'disabled',
    ]],
    'allowlisted network access with a domain secret' => [[
        'allowed_domains' => ['api.example.com'],
        'type' => 'allowlist',
        'domain_secrets' => [[
            'domain' => 'api.example.com',
            'name' => 'EXAMPLE_API_KEY',
            'value' => 'secret_123',
        ]],
    ]],
]);
