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
 * @phpstan-type OutputCustomToolCallItemType array{call_id: string, id: string, input: string, name: string, status: 'in_progress'|'completed'|'incomplete', type: 'custom_tool_call', caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, namespace?: string|null, created_by?: string|null}
 *
 * @implements ResponseContract<OutputCustomToolCallItemType>
 */
final class OutputCustomToolCallItem implements ResponseContract
{
    /** @use ArrayAccessible<OutputCustomToolCallItemType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'in_progress'|'completed'|'incomplete'  $status
     * @param  'custom_tool_call'  $type
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $input,
        public readonly string $name,
        public readonly string $id,
        public readonly string $type,
        public readonly string $status,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $namespace,
        public readonly ?string $createdBy,
    ) {}

    /** @param OutputCustomToolCallItemType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            input: $attributes['input'],
            name: $attributes['name'],
            id: $attributes['id'],
            type: $attributes['type'],
            status: $attributes['status'],
            caller: isset($attributes['caller'])
                ? ToolCallCallerObjects::parse($attributes['caller'])
                : null,
            namespace: $attributes['namespace'] ?? null,
            createdBy: $attributes['created_by'] ?? null,
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $result = [
            'type' => $this->type,
            'call_id' => $this->callId,
            'input' => $this->input,
            'name' => $this->name,
            'id' => $this->id,
        ];

        if ($this->namespace !== null) {
            $result['namespace'] = $this->namespace;
        }

        if ($this->caller !== null) {
            $result['caller'] = $this->caller->toArray();
        }

        $result['status'] = $this->status;

        if ($this->createdBy !== null) {
            $result['created_by'] = $this->createdBy;
        }

        return $result;
    }
}
