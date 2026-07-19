<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Actions\Responses\ToolCallCallerObjects;
use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Responses\DirectToolCallCaller;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type DirectToolCallCallerType from DirectToolCallCaller
 * @phpstan-import-type ProgrammaticToolCallCallerType from ProgrammaticToolCallCaller
 *
 * @phpstan-type OutputFunctionToolCallType array{arguments: string, call_id: string, name: string, type: 'function_call', id?: string|null, status?: 'in_progress'|'completed'|'incomplete', caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, namespace?: string|null, created_by?: string|null}
 *
 * @implements ResponseContract<OutputFunctionToolCallType>
 */
final class OutputFunctionToolCall implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputFunctionToolCallType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'function_call'  $type
     * @param  'in_progress'|'completed'|'incomplete'|null  $status
     */
    private function __construct(
        public readonly string $arguments,
        public readonly string $callId,
        public readonly string $name,
        public readonly string $type,
        public readonly ?string $id,
        public readonly ?string $status,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $namespace,
        public readonly ?string $createdBy,
    ) {}

    /**
     * @param  OutputFunctionToolCallType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            arguments: $attributes['arguments'],
            callId: $attributes['call_id'],
            name: $attributes['name'],
            type: $attributes['type'],
            id: $attributes['id'] ?? null,
            status: $attributes['status'] ?? null,
            caller: isset($attributes['caller'])
                ? ToolCallCallerObjects::parse($attributes['caller'])
                : null,
            namespace: $attributes['namespace'] ?? null,
            createdBy: $attributes['created_by'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'arguments' => $this->arguments,
            'call_id' => $this->callId,
            'name' => $this->name,
            'type' => $this->type,
        ];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->namespace !== null) {
            $result['namespace'] = $this->namespace;
        }

        if ($this->status !== null) {
            $result['status'] = $this->status;
        }

        if ($this->caller !== null) {
            $result['caller'] = $this->caller->toArray();
        }

        if ($this->createdBy !== null) {
            $result['created_by'] = $this->createdBy;
        }

        return $result;
    }
}
