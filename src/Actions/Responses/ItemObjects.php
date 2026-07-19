<?php

declare(strict_types=1);

namespace OpenAI\Actions\Responses;

use OpenAI\Responses\Responses\Input\ComputerToolCallOutput;
use OpenAI\Responses\Responses\Input\InputMessage;
use OpenAI\Responses\Responses\Input\LocalShellCallOutput;
use OpenAI\Responses\Responses\Input\McpApprovalResponse;
use OpenAI\Responses\Responses\Output\OutputAdditionalTools;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCall;
use OpenAI\Responses\Responses\Output\OutputApplyPatchCallOutput;
use OpenAI\Responses\Responses\Output\OutputCodeInterpreterToolCall;
use OpenAI\Responses\Responses\Output\OutputCompaction;
use OpenAI\Responses\Responses\Output\OutputComputerToolCall;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallItem;
use OpenAI\Responses\Responses\Output\OutputCustomToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputFileSearchToolCall;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallItem;
use OpenAI\Responses\Responses\Output\OutputFunctionToolCallOutput;
use OpenAI\Responses\Responses\Output\OutputImageGenerationToolCall;
use OpenAI\Responses\Responses\Output\OutputLocalShellCall;
use OpenAI\Responses\Responses\Output\OutputMcpApprovalRequest;
use OpenAI\Responses\Responses\Output\OutputMcpCall;
use OpenAI\Responses\Responses\Output\OutputMcpListTools;
use OpenAI\Responses\Responses\Output\OutputMessage;
use OpenAI\Responses\Responses\Output\OutputProgram;
use OpenAI\Responses\Responses\Output\OutputProgramOutput;
use OpenAI\Responses\Responses\Output\OutputReasoning;
use OpenAI\Responses\Responses\Output\OutputShellCall;
use OpenAI\Responses\Responses\Output\OutputShellCallOutput;
use OpenAI\Responses\Responses\Output\OutputToolSearchCall;
use OpenAI\Responses\Responses\Output\OutputToolSearchOutput;
use OpenAI\Responses\Responses\Output\OutputWebSearchToolCall;

/**
 * @phpstan-import-type InputMessageType from InputMessage
 * @phpstan-import-type ComputerToolCallOutputType from ComputerToolCallOutput
 * @phpstan-import-type OutputFunctionToolCallOutputType from OutputFunctionToolCallOutput
 * @phpstan-import-type LocalShellCallOutputType from LocalShellCallOutput
 * @phpstan-import-type McpApprovalResponseType from McpApprovalResponse
 * @phpstan-import-type OutputCustomToolCallOutputType from OutputCustomToolCallOutput
 * @phpstan-import-type OutputComputerToolCallType from OutputComputerToolCall
 * @phpstan-import-type OutputApplyPatchCallType from OutputApplyPatchCall
 * @phpstan-import-type OutputAdditionalToolsType from OutputAdditionalTools
 * @phpstan-import-type OutputApplyPatchCallOutputType from OutputApplyPatchCallOutput
 * @phpstan-import-type OutputFileSearchToolCallType from OutputFileSearchToolCall
 * @phpstan-import-type OutputFunctionToolCallItemType from OutputFunctionToolCallItem
 * @phpstan-import-type OutputMessageType from OutputMessage
 * @phpstan-import-type OutputReasoningType from OutputReasoning
 * @phpstan-import-type OutputShellCallType from OutputShellCall
 * @phpstan-import-type OutputShellCallOutputType from OutputShellCallOutput
 * @phpstan-import-type OutputToolSearchCallType from OutputToolSearchCall
 * @phpstan-import-type OutputToolSearchOutputType from OutputToolSearchOutput
 * @phpstan-import-type OutputWebSearchToolCallType from OutputWebSearchToolCall
 * @phpstan-import-type OutputMcpListToolsType from OutputMcpListTools
 * @phpstan-import-type OutputMcpApprovalRequestType from OutputMcpApprovalRequest
 * @phpstan-import-type OutputMcpCallType from OutputMcpCall
 * @phpstan-import-type OutputImageGenerationToolCallType from OutputImageGenerationToolCall
 * @phpstan-import-type OutputCodeInterpreterToolCallType from OutputCodeInterpreterToolCall
 * @phpstan-import-type OutputCompactionType from OutputCompaction
 * @phpstan-import-type OutputLocalShellCallType from OutputLocalShellCall
 * @phpstan-import-type OutputCustomToolCallItemType from OutputCustomToolCallItem
 * @phpstan-import-type OutputProgramType from OutputProgram
 * @phpstan-import-type OutputProgramOutputType from OutputProgramOutput
 *
 * @phpstan-type ResponseItemObjectTypes array<int, InputMessageType|ComputerToolCallOutputType|OutputFunctionToolCallOutputType|LocalShellCallOutputType|McpApprovalResponseType|OutputCustomToolCallOutputType|OutputAdditionalToolsType|OutputApplyPatchCallType|OutputApplyPatchCallOutputType|OutputCompactionType|OutputComputerToolCallType|OutputFileSearchToolCallType|OutputFunctionToolCallItemType|OutputMessageType|OutputProgramType|OutputProgramOutputType|OutputReasoningType|OutputShellCallType|OutputShellCallOutputType|OutputToolSearchCallType|OutputToolSearchOutputType|OutputWebSearchToolCallType|OutputMcpListToolsType|OutputMcpApprovalRequestType|OutputMcpCallType|OutputImageGenerationToolCallType|OutputCodeInterpreterToolCallType|OutputLocalShellCallType|OutputCustomToolCallItemType>
 * @phpstan-type ResponseItemObjectReturnType array<int, InputMessage|ComputerToolCallOutput|OutputFunctionToolCallOutput|LocalShellCallOutput|McpApprovalResponse|OutputCustomToolCallOutput|OutputAdditionalTools|OutputApplyPatchCall|OutputApplyPatchCallOutput|OutputCompaction|OutputMessage|OutputComputerToolCall|OutputFileSearchToolCall|OutputWebSearchToolCall|OutputFunctionToolCallItem|OutputProgram|OutputProgramOutput|OutputReasoning|OutputShellCall|OutputShellCallOutput|OutputToolSearchCall|OutputToolSearchOutput|OutputMcpListTools|OutputMcpApprovalRequest|OutputMcpCall|OutputImageGenerationToolCall|OutputCodeInterpreterToolCall|OutputLocalShellCall|OutputCustomToolCallItem>
 */
final class ItemObjects
{
    /**
     * @param  ResponseItemObjectTypes  $outputItems
     * @return ResponseItemObjectReturnType
     */
    public static function parse(array $outputItems): array
    {
        return array_map(
            fn (array $item): InputMessage|ComputerToolCallOutput|OutputFunctionToolCallOutput|LocalShellCallOutput|McpApprovalResponse|OutputCustomToolCallOutput|OutputAdditionalTools|OutputApplyPatchCall|OutputApplyPatchCallOutput|OutputCompaction|OutputMessage|OutputComputerToolCall|OutputFileSearchToolCall|OutputWebSearchToolCall|OutputFunctionToolCallItem|OutputProgram|OutputProgramOutput|OutputReasoning|OutputShellCall|OutputShellCallOutput|OutputToolSearchCall|OutputToolSearchOutput|OutputMcpListTools|OutputMcpApprovalRequest|OutputMcpCall|OutputImageGenerationToolCall|OutputCodeInterpreterToolCall|OutputLocalShellCall|OutputCustomToolCallItem => match ($item['type']) {
                'message' => $item['role'] === 'assistant' ? OutputMessage::from($item) : InputMessage::from($item),
                'file_search_call' => OutputFileSearchToolCall::from($item),
                'function_call' => OutputFunctionToolCallItem::from($item),
                'program' => OutputProgram::from($item),
                'program_output' => OutputProgramOutput::from($item),
                'additional_tools' => OutputAdditionalTools::from($item),
                'compaction' => OutputCompaction::from($item),
                'function_call_output' => OutputFunctionToolCallOutput::from($item),
                'web_search_call' => OutputWebSearchToolCall::from($item),
                'computer_call' => OutputComputerToolCall::from($item),
                'computer_call_output' => ComputerToolCallOutput::from($item),
                'reasoning' => OutputReasoning::from($item),
                'mcp_list_tools' => OutputMcpListTools::from($item),
                'mcp_approval_request' => OutputMcpApprovalRequest::from($item),
                'mcp_call' => OutputMcpCall::from($item),
                'image_generation_call' => OutputImageGenerationToolCall::from($item),
                'code_interpreter_call' => OutputCodeInterpreterToolCall::from($item),
                'local_shell_call' => OutputLocalShellCall::from($item),
                'custom_tool_call' => OutputCustomToolCallItem::from($item),
                'local_shell_call_output' => LocalShellCallOutput::from($item),
                'custom_tool_call_output' => OutputCustomToolCallOutput::from($item),
                'shell_call' => OutputShellCall::from($item),
                'shell_call_output' => OutputShellCallOutput::from($item),
                'apply_patch_call' => OutputApplyPatchCall::from($item),
                'apply_patch_call_output' => OutputApplyPatchCallOutput::from($item),
                'tool_search_call' => OutputToolSearchCall::from($item),
                'tool_search_output' => OutputToolSearchOutput::from($item),
                'mcp_approval_response' => McpApprovalResponse::from($item),
                default => throw new \UnexpectedValueException('Uh oh! We do not recognize this type. Please submit a bug to openai-php/client on GitHub!'),
            },
            $outputItems,
        );
    }
}
