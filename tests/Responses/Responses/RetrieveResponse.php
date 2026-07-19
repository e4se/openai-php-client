<?php

use OpenAI\Responses\Meta\MetaInformation;
use OpenAI\Responses\Responses\CreateResponseFormat;
use OpenAI\Responses\Responses\CreateResponseReasoning;
use OpenAI\Responses\Responses\CreateResponseUsage;
use OpenAI\Responses\Responses\Input\ComputerToolCallOutput;
use OpenAI\Responses\Responses\Input\McpApprovalResponse;
use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;
use OpenAI\Responses\Responses\Output\OutputComputerToolCall;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\RetrieveResponse;
use OpenAI\Responses\Responses\Tool\ComputerTool;
use OpenAI\Responses\Responses\Tool\WebSearchTool;
use OpenAI\Responses\Responses\ToolChoice\AllowedToolsToolChoice;
use OpenAI\Responses\Responses\ToolChoice\CustomToolChoice;
use OpenAI\Responses\Responses\ToolChoice\HostedToolChoice;
use OpenAI\Responses\Responses\ToolChoice\McpToolChoice;

test('from', function () {
    $result = RetrieveResponse::from(retrieveResponseResource(), meta());

    expect($result)
        ->toBeInstanceOf(RetrieveResponse::class)
        ->id->toBe('resp_67ccf18ef5fc8190b16dbee19bc54e5f087bb177ab789d5c')
        ->object->toBe('response')
        ->createdAt->toBe(1741484430)
        ->status->toBe('completed')
        ->error->toBeNull()
        ->incompleteDetails->toBeNull()
        ->instructions->toBeNull()
        ->maxOutputTokens->toBeNull()
        ->model->toBe('gpt-4o-2024-08-06')
        ->output->toBeArray()
        ->output->toHaveCount(2)
        ->parallelToolCalls->toBeTrue()
        ->previousResponseId->toBeNull()
        ->prompt->toBeNull()
        ->reasoning->toBeInstanceOf(CreateResponseReasoning::class)
        ->store->toBeTrue()
        ->temperature->toBe(1.0)
        ->text->toBeInstanceOf(CreateResponseFormat::class)
        ->toolChoice->toBe('auto')
        ->tools->toBeArray()
        ->tools->toHaveCount(5)
        ->topP->toBe(1.0)
        ->truncation->toBe('disabled')
        ->usage->toBeInstanceOf(CreateResponseUsage::class)
        ->user->toBeNull()
        ->metadata->toBe([]);

    expect($result->meta())
        ->toBeInstanceOf(MetaInformation::class);
});

test('as array accessible', function () {
    $result = RetrieveResponse::from(retrieveResponseResource(), meta());

    expect($result['id'])->toBe('resp_67ccf18ef5fc8190b16dbee19bc54e5f087bb177ab789d5c');
});

test('to array', function () {
    $result = RetrieveResponse::from(retrieveResponseResource(), meta());

    expect($result->toArray())
        ->toBe(retrieveResponseResource());
});

test('fake', function () {
    $response = RetrieveResponse::fake();

    expect($response)
        ->id->toBe('resp_67ccf18ef5fc8190b16dbee19bc54e5f087bb177ab789d5c')
        ->object->toBe('response')
        ->status->toBe('completed');
});

test('fake with override', function () {
    $response = RetrieveResponse::fake([
        'id' => 'resp_1234',
        'object' => 'custom_response',
        'status' => 'failed',
    ]);

    expect($response)
        ->id->toBe('resp_1234')
        ->object->toBe('custom_response')
        ->status->toBe('failed');
});

test('forced programmatic tool choice', function () {
    $payload = retrieveResponseResource();
    $payload['tool_choice'] = toolProgrammaticToolCalling();

    $response = RetrieveResponse::from($payload, meta());

    expect($response->toolChoice)
        ->toBeInstanceOf(HostedToolChoice::class)
        ->type->toBe('programmatic_tool_calling');

    expect($response->toArray()['tool_choice'])
        ->toBe(toolProgrammaticToolCalling());
});

test('forced hosted tool choice', function (string $type) {
    $payload = retrieveResponseResource();
    $payload['tool_choice'] = ['type' => $type];

    $response = RetrieveResponse::from($payload, meta());

    expect($response->toolChoice)
        ->toBeInstanceOf(HostedToolChoice::class)
        ->type->toBe($type);

    expect($response->toArray()['tool_choice'])
        ->toBe(['type' => $type]);
})->with([
    'shell',
    'apply_patch',
    'computer',
    'computer_use',
    'web_search_preview_2025_03_11',
    'image_generation',
    'code_interpreter',
]);

test('forced structured tool choice', function (array $toolChoice, string $class) {
    $payload = retrieveResponseResource();
    $payload['tool_choice'] = $toolChoice;

    $response = RetrieveResponse::from($payload, meta());

    expect($response->toolChoice)->toBeInstanceOf($class);
    expect($response->toArray()['tool_choice'])->toBe($toolChoice);
})->with([
    'custom' => [
        ['name' => 'inventory', 'type' => 'custom'],
        CustomToolChoice::class,
    ],
    'mcp' => [
        ['server_label' => 'inventory', 'type' => 'mcp', 'name' => 'lookup'],
        McpToolChoice::class,
    ],
    'allowed tools' => [
        [
            'mode' => 'required',
            'tools' => [
                ['name' => 'get_inventory', 'type' => 'function'],
            ],
            'type' => 'allowed_tools',
        ],
        AllowedToolsToolChoice::class,
    ],
]);

test('programmatic tool result items', function () {
    $payload = retrieveResponseResource();
    $payload['output'] = [
        functionToolCallOutputProgrammatic(),
        customToolCallOutputProgrammatic(),
    ];
    $payload['output'][0]['created_by'] = 'actor_function';
    $payload['output'][1]['created_by'] = 'actor_custom';

    $response = RetrieveResponse::from($payload, meta());

    expect($response)
        ->output->toHaveCount(2)
        ->output->{0}->toBeInstanceOf(OutputFunctionToolCallOutput::class)
        ->output->{0}->createdBy->toBe('actor_function')
        ->output->{1}->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->output->{1}->createdBy->toBe('actor_custom')
        ->output->{1}->status->toBe('completed');

    expect($response->toArray()['output'])
        ->toBe($payload['output']);
});

test('computer and MCP output items plus current hosted tools', function () {
    $payload = retrieveResponseResource();
    $payload['output'] = [
        outputComputerToolCallGa(),
        computerToolCallOutputItem(),
        mcpApprovalResponseItem(),
    ];
    $payload['tools'] = [
        ['type' => 'computer'],
        ['type' => 'web_search_2025_08_26'],
    ];

    $response = RetrieveResponse::from($payload, meta());

    expect($response)
        ->output->toHaveCount(3)
        ->output->{0}->toBeInstanceOf(OutputComputerToolCall::class)
        ->output->{0}->actions->toHaveCount(2)
        ->output->{1}->toBeInstanceOf(ComputerToolCallOutput::class)
        ->output->{2}->toBeInstanceOf(McpApprovalResponse::class)
        ->tools->toHaveCount(2)
        ->tools->{0}->toBeInstanceOf(ComputerTool::class)
        ->tools->{1}->toBeInstanceOf(WebSearchTool::class);

    expect($response->toArray()['output'])->toBe($payload['output']);
    expect($response->toArray()['tools'])->toBe($payload['tools']);
});

test('programmatic shell and apply patch variants', function () {
    $payload = retrieveResponseResource();
    $payload['output'] = [
        outputShellCall(),
        outputShellCallProgrammatic(),
        outputApplyPatchCallProgrammatic(),
    ];
    $payload['tools'] = [
        toolShellProgrammatic(),
        toolApplyPatchProgrammatic(),
    ];

    $response = RetrieveResponse::from($payload, meta());

    expect($response->output)
        ->{0}->toBeInstanceOf(OutputShellCall::class)
        ->{0}->id->toBeNull()
        ->{1}->toBeInstanceOf(OutputShellCall::class)
        ->{2}->toBeInstanceOf(OutputApplyPatchCall::class);

    expect($response->toArray()['output'])->toBe($payload['output']);
    expect($response->toArray()['tools'])->toBe($payload['tools']);
});

test('additional tools output item', function () {
    $payload = retrieveResponseResource();
    $payload['output'] = [outputAdditionalTools()];

    $response = RetrieveResponse::from($payload, meta());

    expect($response->output)
        ->toHaveCount(1)
        ->{0}->toBeInstanceOf(OutputAdditionalTools::class);

    expect($response->toArray()['output'])->toBe($payload['output']);
});
