<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Responses\GenericResponseError;
use OpenAI\Responses\Responses\McpGenericResponseError;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type ErrorType from GenericResponseError
 * @phpstan-import-type McpErrorType from McpGenericResponseError
 *
 * @phpstan-type OutputMcpCallType array{id: string, server_label: string, type: 'mcp_call', approval_request_id?: string|null, arguments: string, error?: string|McpErrorType|ErrorType|null, name: string, output?: string|null, status?: 'in_progress'|'completed'|'incomplete'|'calling'|'failed'|null}
 *
 * @implements ResponseContract<OutputMcpCallType>
 */
final class OutputMcpCall implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputMcpCallType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'mcp_call'  $type
     * @param  'in_progress'|'completed'|'incomplete'|'calling'|'failed'|null  $status
     * @param  string|McpErrorType|ErrorType|null  $serializedError
     */
    private function __construct(
        public readonly string $id,
        public readonly string $serverLabel,
        public readonly string $type,
        public readonly string $arguments,
        public readonly string $name,
        public readonly ?string $approvalRequestId = null,
        public readonly McpGenericResponseError|GenericResponseError|null $error = null,
        public readonly ?string $output = null,
        public readonly ?string $status = null,
        private readonly string|array|null $serializedError = null,
        private readonly bool $hasApprovalRequestId = false,
        private readonly bool $hasError = false,
        private readonly bool $hasOutput = false,
        private readonly bool $hasStatus = false,
    ) {}

    /**
     * @param  OutputMcpCallType  $attributes
     */
    public static function from(array $attributes): self
    {
        // OpenAI has odd structure (presumably a bug) where the errorType can sometimes be a full-fledged HTTP error object.
        // They can also be a full-fledged MCP error object.
        // They can also just be a string message. So we need to handle all three cases.
        $errorType = null;
        if (isset($attributes['error'])) {
            if (is_array($attributes['error']) && isset($attributes['error']['content'])) {
                $errorType = McpGenericResponseError::from($attributes['error']);
            } elseif (is_array($attributes['error']) && isset($attributes['error']['message'])) {
                $errorType = GenericResponseError::from($attributes['error']);
            } elseif (is_string($attributes['error'])) {
                $errorType = GenericResponseError::from([
                    'code' => 'unknown_error',
                    'message' => $attributes['error'],
                ]);
            }
        }

        return new self(
            id: $attributes['id'],
            serverLabel: $attributes['server_label'],
            type: $attributes['type'],
            arguments: $attributes['arguments'],
            name: $attributes['name'],
            approvalRequestId: $attributes['approval_request_id'] ?? null,
            error: $errorType,
            output: $attributes['output'] ?? null,
            status: $attributes['status'] ?? null,
            serializedError: $attributes['error'] ?? null,
            hasApprovalRequestId: array_key_exists('approval_request_id', $attributes),
            hasError: array_key_exists('error', $attributes),
            hasOutput: array_key_exists('output', $attributes),
            hasStatus: array_key_exists('status', $attributes),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'id' => $this->id,
            'server_label' => $this->serverLabel,
            'type' => $this->type,
            'arguments' => $this->arguments,
            'name' => $this->name,
        ];

        if ($this->hasApprovalRequestId) {
            $result['approval_request_id'] = $this->approvalRequestId;
        }

        if ($this->hasError) {
            $result['error'] = $this->serializedError;
        }

        if ($this->hasOutput) {
            $result['output'] = $this->output;
        }

        if ($this->hasStatus) {
            $result['status'] = $this->status;
        }

        return $result;
    }
}
