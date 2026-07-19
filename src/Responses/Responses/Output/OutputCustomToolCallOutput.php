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
 * @phpstan-type OutputCustomToolCallOutputType array{call_id: string, id: string, output: string|array<int, array<string, mixed>>, type: 'custom_tool_call_output', status: 'in_progress'|'completed'|'incomplete', caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, created_by?: string|null}
 *
 * @implements ResponseContract<OutputCustomToolCallOutputType>
 */
final class OutputCustomToolCallOutput implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputCustomToolCallOutputType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, array<string, mixed>>|string  $output
     * @param  'custom_tool_call_output'  $type
     * @param  'in_progress'|'completed'|'incomplete'  $status
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $id,
        public readonly array|string $output,
        public readonly string $type,
        public readonly string $status,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $createdBy,
    ) {}

    /**
     * @param  OutputCustomToolCallOutputType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            id: $attributes['id'],
            output: $attributes['output'],
            type: $attributes['type'],
            status: $attributes['status'],
            caller: isset($attributes['caller'])
                ? ToolCallCallerObjects::parse($attributes['caller'])
                : null,
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
            'id' => $this->id,
            'output' => $this->output,
            'status' => $this->status,
        ];

        if ($this->caller !== null) {
            $result['caller'] = $this->caller->toArray();
        }

        if ($this->createdBy !== null) {
            $result['created_by'] = $this->createdBy;
        }

        return $result;
    }
}
