<?php

use OpenAI\Responses\Meta\MetaInformation;
use OpenAI\Responses\Responses\CreateResponse;
use OpenAI\Responses\Responses\CreateResponseFormat;
use OpenAI\Responses\Responses\CreateResponseReasoning;
use OpenAI\Responses\Responses\CreateResponseUsage;
use OpenAI\Responses\Responses\Input\ComputerToolCallOutput;
use OpenAI\Responses\Responses\Input\LocalShellCallOutput;
use OpenAI\Responses\Responses\Input\McpApprovalResponse;
use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCallOutput;
use OpenAI\Responses\Responses\Output\OutputCodeInterpreterToolCall;
use OpenAI\Responses\Responses\Output\OutputComputerToolCall;
use OpenAI\Responses\Responses\Output\OutputCustomToolCall;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputProgram;
use OpenAI\Responses\Responses\Output\OutputProgramOutput;
use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\Output\OutputShellCallOutput;
use OpenAI\Responses\Responses\Tool\ApplyPatchTool;
use OpenAI\Responses\Responses\Tool\ComputerTool;
use OpenAI\Responses\Responses\Tool\FunctionTool;
use OpenAI\Responses\Responses\Tool\LocalShellTool;
use OpenAI\Responses\Responses\Tool\ProgrammaticToolCallingTool;
use OpenAI\Responses\Responses\Tool\ShellTool;
use OpenAI\Responses\Responses\Tool\WebSearchTool;
use OpenAI\Responses\Responses\ToolChoice\AllowedToolsToolChoice;
use OpenAI\Responses\Responses\ToolChoice\CustomToolChoice;
use OpenAI\Responses\Responses\ToolChoice\HostedToolChoice;
use OpenAI\Responses\Responses\ToolChoice\McpToolChoice;
use OpenAI\Testing\Enums\OverrideStrategy;

test('from', function () {
    $response = CreateResponse::from(createResponseResource(), meta());

    expect($response)
        ->toBeInstanceOf(CreateResponse::class)
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
        ->parallelToolCalls->toBeTrue()
        ->previousResponseId->toBeNull()
        ->reasoning->toBeInstanceOf(CreateResponseReasoning::class)
        ->store->toBeTrue()
        ->temperature->toBe(1.0)
        ->text->toBeInstanceOf(CreateResponseFormat::class)
        ->toolChoice->toBe('auto')
        ->tools->toBeArray()
        ->topP->toBe(1.0)
        ->truncation->toBe('disabled')
        ->usage->toBeInstanceOf(CreateResponseUsage::class)
        ->user->toBeNull()
        ->metadata->toBe([]);

    expect($response->meta())
        ->toBeInstanceOf(MetaInformation::class);
});

test('as array accessible', function () {
    $response = CreateResponse::from(createResponseResource(), meta());

    expect($response['id'])->toBe('resp_67ccf18ef5fc8190b16dbee19bc54e5f087bb177ab789d5c');
});

test('to array', function () {
    $response = CreateResponse::from(createResponseResource(), meta());

    $expected = createResponseResource();
    $expected['output_text'] = 'As of today, March 9, 2025, one notable positive news story...';

    expect($response->toArray())
        ->toBeArray()
        ->toBe($expected);
});

test('to array with no messages', function () {
    $payload = createResponseResource();
    $payload['output'] = [
        outputMessageOnlyRefusal(),
    ];

    $response = CreateResponse::from($payload, meta());

    expect($response->toArray())
        ->toBeArray()
        ->outputText->toBeNull();
});

test('fake', function () {
    $response = CreateResponse::fake();

    expect($response)
        ->id->toBe('resp_67ccf18ef5fc8190b16dbee19bc54e5f087bb177ab789d5c');
});

test('fake with the "replace" and "merge" strategy override', function () {
    $attributes = [
        'output' => [
            [
                'id' => 'msg_67ccd2bf17f0819081ff3bb2cf6508e60bb6a6b452d3795b',
                'role' => 'assistant',
                'type' => 'message',
                'status' => 'completed',
                'content' => [
                    [
                        'type' => 'output_text',
                        'text' => 'This is the fake test output',
                        'annotations' => [],
                    ],
                ],
            ],
        ],
    ];
    $response = CreateResponse::fake(
        $attributes,
        strategy: OverrideStrategy::Replace
    );

    expect($response)
        ->output->toBeArray()->toHaveCount(1)
        ->outputText->toBe('This is the fake test output');

    $response = CreateResponse::fake(
        $attributes,
    );

    expect($response)
        ->output->toBeArray()->toHaveCount(2)
        ->outputText->toBe('This is the fake test output As of today, March 9, 2025, one notable positive news story...');
});

test('fake with override', function () {
    $response = CreateResponse::fake([
        'id' => 'resp_1234',
        'object' => 'custom_response',
        'status' => 'failed',
        'output' => [
            outputBasicMessage(),
        ],
    ]);

    expect($response)
        ->id->toBe('resp_1234')
        ->object->toBe('custom_response')
        ->status->toBe('failed')
        ->output->toBeArray();

    expect($response->output[0]['content'][0])
        ->type->toBe('output_text')
        ->text->toBe('This is a basic message.');
});

test('from with missing store field defaults to true', function () {
    $response = CreateResponse::fake();

    expect($response)
        ->toBeInstanceOf(CreateResponse::class)
        ->store->toBeTrue();
});

test('from with null store field defaults to true', function () {
    $response = CreateResponse::fake(['store' => null]);

    expect($response)
        ->toBeInstanceOf(CreateResponse::class)
        ->store->toBeTrue();
});

test('from with false store field', function () {
    $response = CreateResponse::fake(['store' => false]);

    expect($response)
        ->toBeInstanceOf(CreateResponse::class)
        ->store->toBeFalse();
});

test('from with missing text field', function () {
    $response = CreateResponse::fake(['text' => null]);

    expect($response)
        ->toBeInstanceOf(CreateResponse::class)
        ->text->toBeNull();
});

test('from with null text field', function () {
    $response = CreateResponse::fake(['text' => null]);

    expect($response)
        ->toBeInstanceOf(CreateResponse::class)
        ->text->toBeNull();
});

test('to array with null text field', function () {
    $response = CreateResponse::fake(['text' => null]);

    $array = $response->toArray();

    expect($array)
        ->toBeArray()
        ->text->toBeNull();
});

test('programmatic tool calling response', function () {
    $payload = createResponseResource();
    $payload['output'] = [
        outputProgram(),
        outputFunctionToolCallProgrammatic(),
        outputProgramOutput(),
    ];
    $payload['tools'] = [
        toolFunctionProgrammatic(),
        toolProgrammaticToolCalling(),
    ];

    $response = CreateResponse::from($payload, meta());

    expect($response)
        ->output->toHaveCount(3)
        ->output->{0}->toBeInstanceOf(OutputProgram::class)
        ->output->{1}->toBeInstanceOf(OutputFunctionToolCall::class)
        ->output->{1}->namespace->toBe('inventory')
        ->output->{1}->caller->callerId->toBe('call_prog_123')
        ->output->{2}->toBeInstanceOf(OutputProgramOutput::class)
        ->tools->toHaveCount(2)
        ->tools->{0}->toBeInstanceOf(FunctionTool::class)
        ->tools->{0}->allowedCallers->toBe(['programmatic'])
        ->tools->{1}->toBeInstanceOf(ProgrammaticToolCallingTool::class);
});

test('forced programmatic tool choice', function () {
    $payload = createResponseResource();
    $payload['tool_choice'] = toolProgrammaticToolCalling();

    $response = CreateResponse::from($payload, meta());

    expect($response->toolChoice)
        ->toBeInstanceOf(HostedToolChoice::class)
        ->type->toBe('programmatic_tool_calling');

    expect($response->toArray()['tool_choice'])
        ->toBe(toolProgrammaticToolCalling());
});

test('forced hosted tool choice', function (string $type) {
    $payload = createResponseResource();
    $payload['tool_choice'] = ['type' => $type];

    $response = CreateResponse::from($payload, meta());

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
    $payload = createResponseResource();
    $payload['tool_choice'] = $toolChoice;

    $response = CreateResponse::from($payload, meta());

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
    $payload = createResponseResource();
    $payload['output'] = [
        functionToolCallOutputProgrammatic(),
        customToolCallOutputProgrammatic(),
    ];
    $payload['output'][0]['created_by'] = 'actor_function';
    $payload['output'][1]['created_by'] = 'actor_custom';

    $response = CreateResponse::from($payload, meta());

    expect($response)
        ->output->toHaveCount(2)
        ->output->{0}->toBeInstanceOf(OutputFunctionToolCallOutput::class)
        ->output->{0}->createdBy->toBe('actor_function')
        ->output->{0}->caller->callerId->toBe('call_prog_123')
        ->output->{1}->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->output->{1}->createdBy->toBe('actor_custom')
        ->output->{1}->status->toBe('completed')
        ->output->{1}->caller->callerId->toBe('call_prog_123');

    expect($response->toArray()['output'])
        ->toBe($payload['output']);
});

test('response output accepts optional tool call ids and current code interpreter outputs', function () {
    $functionCall = outputFunctionToolCallProgrammatic();
    unset($functionCall['id']);

    $customCall = outputCustomToolCallProgrammatic();
    unset($customCall['id']);

    $codeInterpreterCall = [
        'code' => null,
        'id' => 'ci_image',
        'outputs' => [
            ['type' => 'image', 'url' => 'https://example.com/chart.png'],
        ],
        'status' => 'completed',
        'type' => 'code_interpreter_call',
        'container_id' => 'container_image',
    ];

    $payload = createResponseResource();
    $payload['output'] = [$functionCall, $customCall, $codeInterpreterCall];

    $response = CreateResponse::from($payload, meta());

    expect($response->output)
        ->{0}->toBeInstanceOf(OutputFunctionToolCall::class)
        ->{0}->id->toBeNull()
        ->{1}->toBeInstanceOf(OutputCustomToolCall::class)
        ->{1}->id->toBeNull()
        ->{2}->toBeInstanceOf(OutputCodeInterpreterToolCall::class)
        ->{2}->code->toBeNull();

    expect($response->toArray()['output'])->toBe($payload['output']);
});

test('computer and MCP output items plus current hosted tools', function () {
    $payload = createResponseResource();
    $payload['output'] = [
        outputComputerToolCallGa(),
        computerToolCallOutputItem(),
        mcpApprovalResponseItem(),
    ];
    $payload['tools'] = [
        ['type' => 'computer'],
        ['type' => 'web_search_2025_08_26'],
    ];

    $response = CreateResponse::from($payload, meta());

    expect($response)
        ->output->toHaveCount(3)
        ->output->{0}->toBeInstanceOf(OutputComputerToolCall::class)
        ->output->{0}->actions->toHaveCount(2)
        ->output->{1}->toBeInstanceOf(ComputerToolCallOutput::class)
        ->output->{1}->createdBy->toBe('actor_computer')
        ->output->{2}->toBeInstanceOf(McpApprovalResponse::class)
        ->tools->toHaveCount(2)
        ->tools->{0}->toBeInstanceOf(ComputerTool::class)
        ->tools->{1}->toBeInstanceOf(WebSearchTool::class);

    expect($response->toArray()['output'])->toBe($payload['output']);
    expect($response->toArray()['tools'])->toBe($payload['tools']);
});

test('programmatic tool result items with content array outputs', function () {
    $payload = createResponseResource();
    $content = [
        ['type' => 'input_text', 'text' => 'result'],
        ['type' => 'input_image', 'detail' => 'auto', 'file_id' => 'file_image', 'image_url' => null],
        ['type' => 'input_file', 'file_id' => 'file_document', 'filename' => 'result.txt'],
    ];
    $functionOutput = functionToolCallOutputProgrammatic();
    $functionOutput['output'] = $content;
    $customOutput = customToolCallOutputProgrammatic();
    $customOutput['output'] = $content;
    $payload['output'] = [$functionOutput, $customOutput];

    $response = CreateResponse::from($payload, meta());

    expect($response)
        ->output->{0}->toBeInstanceOf(OutputFunctionToolCallOutput::class)
        ->output->{0}->output->toBe($content)
        ->output->{1}->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->output->{1}->output->toBe($content);

    expect($response->toArray()['output'])
        ->toBe($payload['output']);
});

test('programmatic shell and apply patch variants', function () {
    $payload = createResponseResource();
    $payload['output'] = [
        outputShellCallProgrammatic(),
        outputShellCallOutputProgrammatic(),
        outputApplyPatchCallProgrammatic(),
        outputApplyPatchCallOutputProgrammatic(),
        outputLocalShellCall(),
        localShellCallOutputItem(),
    ];
    $payload['tools'] = [
        toolShellProgrammatic(),
        toolApplyPatchProgrammatic(),
        toolLocalShell(),
        toolProgrammaticToolCalling(),
    ];

    $response = CreateResponse::from($payload, meta());

    expect($response->output)
        ->{0}->toBeInstanceOf(OutputShellCall::class)
        ->{1}->toBeInstanceOf(OutputShellCallOutput::class)
        ->{2}->toBeInstanceOf(OutputApplyPatchCall::class)
        ->{3}->toBeInstanceOf(OutputApplyPatchCallOutput::class)
        ->{5}->toBeInstanceOf(LocalShellCallOutput::class);

    expect($response->tools)
        ->{0}->toBeInstanceOf(ShellTool::class)
        ->{1}->toBeInstanceOf(ApplyPatchTool::class)
        ->{2}->toBeInstanceOf(LocalShellTool::class);

    expect($response->toArray()['output'])->toBe($payload['output']);
    expect($response->toArray()['tools'])->toBe($payload['tools']);
});

test('additional tools output item', function () {
    $payload = createResponseResource();
    $payload['output'] = [outputAdditionalTools()];

    $response = CreateResponse::from($payload, meta());

    expect($response->output)
        ->toHaveCount(1)
        ->{0}->toBeInstanceOf(OutputAdditionalTools::class)
        ->{0}->tools->{0}->toBeInstanceOf(FunctionTool::class);

    expect($response->toArray()['output'])->toBe($payload['output']);
});
