<?php

use OpenAI\Responses\Responses\CreateResponse;
use OpenAI\Responses\Responses\CreateStreamedResponse;
use OpenAI\Responses\Responses\Input\ComputerToolCallOutput;
use OpenAI\Responses\Responses\Input\LocalShellCallOutput;
use OpenAI\Responses\Responses\Input\McpApprovalResponse;
use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCallOutput;
use OpenAI\Responses\Responses\Output\OutputCodeInterpreterToolCall;
use OpenAI\Responses\Responses\Output\OutputCompaction;
use OpenAI\Responses\Responses\Output\OutputCustomToolCall;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputLocalShellCall;
use OpenAI\Responses\Responses\Output\OutputProgram;
use OpenAI\Responses\Responses\Output\OutputProgramOutput;
use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\Output\OutputShellCallOutput;
use OpenAI\Responses\Responses\Output\OutputToolSearchCall;
use OpenAI\Responses\Responses\Output\OutputToolSearchOutput;
use OpenAI\Responses\Responses\Streaming\OutputItem;
use OpenAI\Responses\Responses\Streaming\RateLimits;
use OpenAI\Responses\Responses\Streaming\ReasoningTextDelta;
use OpenAI\Responses\Responses\Streaming\ReasoningTextDone;
use OpenAI\Responses\Responses\Streaming\Response;
use OpenAI\Responses\Responses\ToolChoice\HostedToolChoice;

test('fake', function () {
    $response = CreateStreamedResponse::fake();

    expect($response->getIterator()->current()->response)
        ->toBeInstanceOf(Response::class)
        ->type->toBe('response.created')
        ->response->toBeInstanceOf(CreateResponse::class)
        ->response->id->toBe('resp_67c9fdcecf488190bdd9a0409de3a1ec07b8b0ad4e5eb654');
});

test('from', function () {
    $response = CreateStreamedResponse::fake(responseCompletionSteamCreatedEvent());

    expect($response->getIterator()->current())
        ->toBeInstanceOf(CreateStreamedResponse::class)
        ->event->toBe('response.created')
        ->response->toBeInstanceOf(Response::class)
        ->response->type->toBe('response.created')
        ->response->response->toBeInstanceOf(CreateResponse::class)
        ->response->response->id->toBe('resp_67ccf18ef5fc8190b16dbee19bc54e5f087bb177ab789d5c');
});

test('reasoning text delta event', function () {
    $response = CreateStreamedResponse::fake(responseReasoningTextDeltaEvent());

    expect($response->getIterator()->current())
        ->toBeInstanceOf(CreateStreamedResponse::class)
        ->event->toBe('response.reasoning_text.delta')
        ->response->toBeInstanceOf(ReasoningTextDelta::class)
        ->response->delta->toBe('Let me analyze')
        ->response->itemId->toBe('item_123')
        ->response->outputIndex->toBe(0)
        ->response->contentIndex->toBe(0)
        ->response->sequenceNumber->toBe(5);
});

test('reasoning text done event', function () {
    $response = CreateStreamedResponse::fake(responseReasoningTextDoneEvent());

    expect($response->getIterator()->current())
        ->toBeInstanceOf(CreateStreamedResponse::class)
        ->event->toBe('response.reasoning_text.done')
        ->response->toBeInstanceOf(ReasoningTextDone::class)
        ->response->text->toBe('Let me analyze this problem step by step to provide the best solution.')
        ->response->itemId->toBe('item_123')
        ->response->outputIndex->toBe(0)
        ->response->contentIndex->toBe(0)
        ->response->sequenceNumber->toBe(10);
});

test('rate limits updated event', function () {
    $response = CreateStreamedResponse::fake(responseRateLimitsUpdatedEvent());

    expect($response->getIterator()->current())
        ->toBeInstanceOf(CreateStreamedResponse::class)
        ->event->toBe('response.rate_limits.updated')
        ->response->toBeInstanceOf(RateLimits::class)
        ->response->toArray()->toBe([
            'type' => 'response.rate_limits.updated',
        ]);
});

test('output item done event with compaction item', function () {
    $response = CreateStreamedResponse::fake(responseOutputItemCompactionDoneEvent());

    expect($response->getIterator()->current())
        ->toBeInstanceOf(CreateStreamedResponse::class)
        ->event->toBe('response.output_item.done')
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->outputIndex->toBe(0)
        ->response->sequenceNumber->toBe(11)
        ->response->item->toBeInstanceOf(OutputCompaction::class)
        ->response->item->id->toBe('cmp_67ccf18f64008190a39b619f4c8455ef087bb177ab789d5c')
        ->response->item->encryptedContent->toBe('encrypted_string_value')
        ->response->item->createdBy->toBe('user_123');
});

test('output item added event with program item', function () {
    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.added',
        'output_index' => 0,
        'sequence_number' => 3,
        'item' => outputProgram(),
        '__meta' => meta(),
    ]);

    expect($response)
        ->event->toBe('response.output_item.added')
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->item->toBeInstanceOf(OutputProgram::class)
        ->response->item->callId->toBe('call_prog_123');
});

test('output item added event with nested function call', function () {
    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.added',
        'output_index' => 1,
        'sequence_number' => 4,
        'item' => outputFunctionToolCallProgrammatic(),
        '__meta' => meta(),
    ]);

    expect($response)
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->item->toBeInstanceOf(OutputFunctionToolCall::class)
        ->response->item->namespace->toBe('inventory')
        ->response->item->caller->callerId->toBe('call_prog_123');
});

test('output item done event with program output item', function () {
    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.done',
        'output_index' => 2,
        'sequence_number' => 5,
        'item' => outputProgramOutput(),
        '__meta' => meta(),
    ]);

    expect($response)
        ->event->toBe('response.output_item.done')
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->item->toBeInstanceOf(OutputProgramOutput::class)
        ->response->item->status->toBe('completed');
});

test('output item added event preserves custom tool caller linkage', function () {
    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.added',
        'output_index' => 3,
        'sequence_number' => 6,
        'item' => outputCustomToolCallProgrammatic(),
        '__meta' => meta(),
    ]);

    expect($response)
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->item->toBeInstanceOf(OutputCustomToolCall::class)
        ->response->item->namespace->toBe('inventory')
        ->response->item->caller->callerId->toBe('call_prog_123');
});

test('stream output accepts optional tool call ids and current code interpreter outputs', function () {
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

    $items = [
        [$functionCall, OutputFunctionToolCall::class],
        [$customCall, OutputCustomToolCall::class],
        [$codeInterpreterCall, OutputCodeInterpreterToolCall::class],
    ];

    foreach ($items as $index => [$item, $class]) {
        $response = CreateStreamedResponse::from([
            'type' => 'response.output_item.done',
            'output_index' => $index,
            'sequence_number' => $index + 1,
            'item' => $item,
            '__meta' => meta(),
        ]);

        expect($response->response->item)->toBeInstanceOf($class);
        expect($response->response->toArray()['item'])->toBe($item);
    }
});

test('response event accepts forced programmatic tool choice', function () {
    $payload = createResponseResource();
    $payload['tool_choice'] = toolProgrammaticToolCalling();

    $response = CreateStreamedResponse::from([
        'type' => 'response.created',
        'response' => $payload,
        'sequence_number' => 0,
        '__meta' => meta(),
    ]);

    expect($response)
        ->response->toBeInstanceOf(Response::class)
        ->response->response->toolChoice->toBeInstanceOf(HostedToolChoice::class)
        ->response->response->toolChoice->type->toBe('programmatic_tool_calling');
});

test('output item event accepts function tool result', function () {
    $item = functionToolCallOutputProgrammatic();
    $item['created_by'] = 'actor_function';

    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.done',
        'output_index' => 4,
        'sequence_number' => 7,
        'item' => $item,
        '__meta' => meta(),
    ]);

    expect($response)
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->item->toBeInstanceOf(OutputFunctionToolCallOutput::class)
        ->response->item->createdBy->toBe('actor_function')
        ->response->item->caller->callerId->toBe('call_prog_123');

    expect($response->response->toArray()['item'])
        ->toBe($item);
});

test('output item event accepts custom tool result', function () {
    $item = customToolCallOutputProgrammatic();
    $item['output'] = [
        ['type' => 'input_text', 'text' => 'custom result'],
    ];
    $item['created_by'] = 'actor_custom';

    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.done',
        'output_index' => 5,
        'sequence_number' => 8,
        'item' => $item,
        '__meta' => meta(),
    ]);

    expect($response)
        ->response->toBeInstanceOf(OutputItem::class)
        ->response->item->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->response->item->output->toBe($item['output'])
        ->response->item->createdBy->toBe('actor_custom')
        ->response->item->status->toBe('completed')
        ->response->item->caller->callerId->toBe('call_prog_123');

    expect($response->response->toArray()['item'])
        ->toBe($item);
});

test('output item events accept programmatic tool families', function () {
    $items = [
        [outputToolSearchCall(), OutputToolSearchCall::class],
        [outputToolSearchOutput(), OutputToolSearchOutput::class],
        [outputLocalShellCall(), OutputLocalShellCall::class],
        [localShellCallOutputItem(), LocalShellCallOutput::class],
        [outputShellCallProgrammatic(), OutputShellCall::class],
        [outputShellCallOutputProgrammatic(), OutputShellCallOutput::class],
        [outputApplyPatchCallProgrammatic(), OutputApplyPatchCall::class],
        [outputApplyPatchCallOutputProgrammatic(), OutputApplyPatchCallOutput::class],
    ];

    foreach ($items as $index => [$item, $class]) {
        $response = CreateStreamedResponse::from([
            'type' => 'response.output_item.done',
            'output_index' => $index,
            'sequence_number' => $index + 1,
            'item' => $item,
            '__meta' => meta(),
        ]);

        expect($response->response)
            ->toBeInstanceOf(OutputItem::class)
            ->item->toBeInstanceOf($class);

        expect($response->response->toArray()['item'])->toBe($item);
    }
});

test('output item events accept computer and MCP result variants', function () {
    $items = [
        [computerToolCallOutputItem(), ComputerToolCallOutput::class],
        [mcpApprovalResponseItem(), McpApprovalResponse::class],
    ];

    foreach ($items as $index => [$item, $class]) {
        $response = CreateStreamedResponse::from([
            'type' => 'response.output_item.done',
            'output_index' => $index,
            'sequence_number' => $index + 1,
            'item' => $item,
            '__meta' => meta(),
        ]);

        expect($response->response)
            ->toBeInstanceOf(OutputItem::class)
            ->item->toBeInstanceOf($class);

        expect($response->response->toArray()['item'])->toBe($item);
    }
});

test('output item event accepts additional tools', function () {
    $item = outputAdditionalTools();

    $response = CreateStreamedResponse::from([
        'type' => 'response.output_item.done',
        'output_index' => 0,
        'sequence_number' => 1,
        'item' => $item,
        '__meta' => meta(),
    ]);

    expect($response->response)
        ->toBeInstanceOf(OutputItem::class)
        ->item->toBeInstanceOf(OutputAdditionalTools::class);

    expect($response->response->toArray()['item'])->toBe($item);
});
