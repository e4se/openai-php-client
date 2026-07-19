<?php

use OpenAI\Responses\Meta\MetaInformation;
use OpenAI\Responses\Responses\ListInputItems;
use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCallOutput;
use OpenAI\Responses\Responses\Output\OutputCompaction;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallItem;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallItem;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputProgram;
use OpenAI\Responses\Responses\Output\OutputProgramOutput;
use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\Output\OutputShellCallOutput;
use OpenAI\Responses\Responses\Output\OutputToolSearchCall;
use OpenAI\Responses\Responses\Output\OutputToolSearchOutput;

test('from', function () {
    $result = ListInputItems::from(listInputItemsResource(), meta());

    expect($result)
        ->toBeInstanceOf(ListInputItems::class)
        ->object->toBe('list')
        ->data->toBeArray()
        ->data->toHaveCount(13)
        ->firstId->toBe('msg_67ccf190ca3881909d433c50b1f6357e087bb177ab789d5c')
        ->lastId->toBe('msg_67ccf190ca3881909d433c50b1f6357e087bb177ab789d5c')
        ->hasMore->toBeFalse()
        ->meta()->toBeInstanceOf(MetaInformation::class);
});

test('as array accessible', function () {
    $result = ListInputItems::from(listInputItemsResource(), meta());

    expect($result['object'])->toBe('list');
});

test('to array', function () {
    $result = ListInputItems::from(listInputItemsResource(), meta());

    expect($result->toArray())
        ->toBeArray()
        ->toBe(listInputItemsResource());
});

test('fake', function () {
    $response = ListInputItems::fake();

    expect($response)
        ->object->toBe('list')
        ->firstId->toBe('msg_67ccf190ca3881909d433c50b1f6357e087bb177ab789d5c')
        ->hasMore->toBeFalse();
});

test('fake with override', function () {
    $response = ListInputItems::fake([
        'object' => 'custom_list',
        'first_id' => 'msg_1234',
        'has_more' => true,
    ]);

    expect($response)
        ->object->toBe('custom_list')
        ->firstId->toBe('msg_1234')
        ->hasMore->toBeTrue();
});

test('program items can be replayed as input', function () {
    $payload = listInputItemsResource();
    $customCall = outputCustomToolCallProgrammatic();
    $customCall['status'] = 'completed';
    $payload['data'] = [
        outputProgram(),
        outputProgramOutput(),
        functionToolCallOutputProgrammatic(),
        $customCall,
        customToolCallOutputProgrammatic(),
        outputShellCallProgrammatic(),
        outputShellCallOutputProgrammatic(),
        outputApplyPatchCallProgrammatic(),
        outputApplyPatchCallOutputProgrammatic(),
        outputToolSearchCall(),
        outputToolSearchOutput(),
    ];

    $result = ListInputItems::from($payload, meta());

    expect($result)
        ->data->toHaveCount(11)
        ->data->{0}->toBeInstanceOf(OutputProgram::class)
        ->data->{1}->toBeInstanceOf(OutputProgramOutput::class)
        ->data->{2}->caller->callerId->toBe('call_prog_123')
        ->data->{3}->toBeInstanceOf(OutputCustomToolCallItem::class)
        ->data->{3}->caller->callerId->toBe('call_prog_123')
        ->data->{4}->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->data->{4}->caller->callerId->toBe('call_prog_123')
        ->data->{5}->toBeInstanceOf(OutputShellCall::class)
        ->data->{6}->toBeInstanceOf(OutputShellCallOutput::class)
        ->data->{7}->toBeInstanceOf(OutputApplyPatchCall::class)
        ->data->{8}->toBeInstanceOf(OutputApplyPatchCallOutput::class)
        ->data->{9}->toBeInstanceOf(OutputToolSearchCall::class)
        ->data->{10}->toBeInstanceOf(OutputToolSearchOutput::class);

    expect($result->toArray()['data'])->toBe($payload['data']);
});

test('listed tool items preserve provenance', function () {
    $functionCall = outputFunctionToolCallProgrammatic();
    $functionCall['status'] = 'completed';
    $functionCall['created_by'] = 'actor_function_call';

    $customCall = outputCustomToolCallProgrammatic();
    $customCall['status'] = 'completed';
    $customCall['created_by'] = 'actor_custom_call';

    $functionOutput = functionToolCallOutputProgrammatic();
    $functionOutput['created_by'] = 'actor_function_output';

    $customOutput = customToolCallOutputProgrammatic();
    $customOutput['created_by'] = 'actor_custom_output';

    $payload = listInputItemsResource();
    $payload['data'] = [$functionCall, $customCall, $functionOutput, $customOutput];

    $result = ListInputItems::from($payload, meta());

    expect($result->data)
        ->{0}->toBeInstanceOf(OutputFunctionToolCallItem::class)
        ->{0}->createdBy->toBe('actor_function_call')
        ->{1}->toBeInstanceOf(OutputCustomToolCallItem::class)
        ->{1}->status->toBe('completed')
        ->{1}->createdBy->toBe('actor_custom_call')
        ->{2}->toBeInstanceOf(OutputFunctionToolCallOutput::class)
        ->{2}->createdBy->toBe('actor_function_output')
        ->{3}->toBeInstanceOf(OutputCustomToolCallOutput::class)
        ->{3}->createdBy->toBe('actor_custom_output');

    expect($result->toArray()['data'])->toBe($payload['data']);
});

test('additional tools can be replayed as input', function () {
    $payload = listInputItemsResource();
    $payload['data'] = [outputAdditionalTools()];

    $result = ListInputItems::from($payload, meta());

    expect($result->data)
        ->toHaveCount(1)
        ->{0}->toBeInstanceOf(OutputAdditionalTools::class);

    expect($result->toArray()['data'])->toBe($payload['data']);
});

test('compaction items can be replayed as input', function () {
    $payload = listInputItemsResource();
    $payload['data'] = [outputCompaction()];

    $result = ListInputItems::from($payload, meta());

    expect($result->data)
        ->toHaveCount(1)
        ->{0}->toBeInstanceOf(OutputCompaction::class);

    expect($result->toArray()['data'])->toBe($payload['data']);
});
