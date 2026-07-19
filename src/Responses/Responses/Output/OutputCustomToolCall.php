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
 * @phpstan-type OutputCustomToolCallType array{call_id: string, input: string, name: string, type: 'custom_tool_call', id?: string|null, status?: 'in_progress'|'completed'|'incomplete', caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, namespace?: string|null, created_by?: string|null}
 *
 * @implements ResponseContract<OutputCustomToolCallType>
 */
final class OutputCustomToolCall implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputCustomToolCallType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'custom_tool_call'  $type
     * @param  'in_progress'|'completed'|'incomplete'|null  $status
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $input,
        public readonly string $name,
        public readonly ?string $id,
        public readonly string $type,
        public readonly ?string $status,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $namespace,
        public readonly ?string $createdBy,
    ) {}

    /**
     * @param  OutputCustomToolCallType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            input: $attributes['input'],
            name: $attributes['name'],
            id: $attributes['id'] ?? null,
            type: $attributes['type'],
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
            'type' => $this->type,
            'call_id' => $this->callId,
            'input' => $this->input,
            'name' => $this->name,
        ];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->namespace !== null) {
            $result['namespace'] = $this->namespace;
        }

        if ($this->caller !== null) {
            $result['caller'] = $this->caller->toArray();
        }

        if ($this->status !== null) {
            $result['status'] = $this->status;
        }

        if ($this->createdBy !== null) {
            $result['created_by'] = $this->createdBy;
        }

        return $result;
    }
}
