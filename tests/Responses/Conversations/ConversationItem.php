<?php

use OpenAI\Responses\Conversations\ConversationItem;
use OpenAI\Responses\Conversations\Objects\Message;
use OpenAI\Responses\Responses\Input\CustomToolCallOutput;
use OpenAI\Responses\Responses\Input\FunctionToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCallOutput;
use OpenAI\Responses\Responses\Output\OutputCompaction;
use OpenAI\Responses\Responses\Output\OutputCustomToolCall;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;
use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\Output\OutputShellCallOutput;
use OpenAI\Responses\Responses\Output\OutputToolSearchCall;
use OpenAI\Responses\Responses\Output\OutputToolSearchOutput;

test('from', function () {
    $response = ConversationItem::from(conversationItemResource());

    expect($response)
        ->toBeInstanceOf(ConversationItem::class)
        ->item->toBeInstanceOf(Message::class)
        ->item->id->toBe('msg_abc');
});

test('as array accessible', function () {
    $response = ConversationItem::from(conversationItemResource());

    expect($response['id'])
        ->toBe('msg_abc');
});

test('to array', function () {
    $response = ConversationItem::from(conversationItemResource());

    expect($response->toArray())
        ->toBe(conversationItemResource());
});

test('programmatic shell and apply patch items', function () {
    $items = [
        [outputShellCallProgrammatic(), OutputShellCall::class],
        [outputShellCallOutputProgrammatic(), OutputShellCallOutput::class],
        [outputApplyPatchCallProgrammatic(), OutputApplyPatchCall::class],
        [outputApplyPatchCallOutputProgrammatic(), OutputApplyPatchCallOutput::class],
        [outputToolSearchCall(), OutputToolSearchCall::class],
        [outputToolSearchOutput(), OutputToolSearchOutput::class],
    ];

    foreach ($items as [$attributes, $class]) {
        $response = ConversationItem::from($attributes);

        expect($response->item)->toBeInstanceOf($class);
        expect($response->toArray())->toBe($attributes);
    }
});

test('tool items preserve provenance', function () {
    $functionCall = outputFunctionToolCallProgrammatic();
    $functionCall['created_by'] = 'actor_function_call';

    $customCall = outputCustomToolCallProgrammatic();
    $customCall['status'] = 'completed';
    $customCall['created_by'] = 'actor_custom_call';

    $functionOutput = functionToolCallOutputProgrammatic();
    $functionOutput['created_by'] = 'actor_function_output';

    $customOutput = customToolCallOutputProgrammatic();
    $customOutput['created_by'] = 'actor_custom_output';

    $items = [
        [$functionCall, OutputFunctionToolCall::class],
        [$customCall, OutputCustomToolCall::class],
        [$functionOutput, FunctionToolCallOutput::class],
        [$customOutput, CustomToolCallOutput::class],
    ];

    foreach ($items as [$attributes, $class]) {
        $response = ConversationItem::from($attributes);

        expect($response->item)
            ->toBeInstanceOf($class)
            ->createdBy->toStartWith('actor_');
        expect($response->toArray())->toBe($attributes);
    }
});

test('additional tools item', function () {
    $attributes = outputAdditionalTools();

    $response = ConversationItem::from($attributes);

    expect($response->item)->toBeInstanceOf(OutputAdditionalTools::class);
    expect($response->toArray())->toBe($attributes);
});

test('custom calls and outputs accept missing ids', function () {
    $customCall = outputCustomToolCallProgrammatic();
    unset($customCall['id']);

    $customOutput = customToolCallOutputProgrammatic();
    unset($customOutput['id']);

    foreach ([$customCall, $customOutput] as $attributes) {
        $response = ConversationItem::from($attributes);

        expect($response->item->id)->toBeNull();
        expect($response->toArray())->toBe($attributes);
    }
});

test('compaction item', function () {
    $attributes = outputCompaction();

    $response = ConversationItem::from($attributes);

    expect($response->item)->toBeInstanceOf(OutputCompaction::class);
    expect($response->toArray())->toBe($attributes);
});
