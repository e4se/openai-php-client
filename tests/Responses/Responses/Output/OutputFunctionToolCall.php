<?php

use OpenAI\Responses\Responses\Output\OutputFunctionToolCall;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallCaller;

test('from with program caller', function () {
    $response = OutputFunctionToolCall::from(outputFunctionToolCallFromProgram());

    expect($response)
        ->toBeInstanceOf(OutputFunctionToolCall::class)
        ->type->toBe('function_call')
        ->status->toBeNull()
        ->caller->toBeInstanceOf(OutputFunctionToolCallCaller::class)
        ->caller->type->toBe('program')
        ->caller->callerId->toBe('call_prog_123');
});

test('to array with program caller', function () {
    $response = OutputFunctionToolCall::from(outputFunctionToolCallFromProgram());

    expect($response->toArray())
        ->toBe(outputFunctionToolCallFromProgram());
});
