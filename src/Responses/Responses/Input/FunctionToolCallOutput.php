<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Input;

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
 * @phpstan-type FunctionToolCallOutputType array{call_id: string, id: string, output: string|array<int, array<string, mixed>>, type: 'function_call_output', status?: 'in_progress'|'completed'|'incomplete', caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, created_by?: string|null}
 *
 * @implements ResponseContract<FunctionToolCallOutputType>
 */
final class FunctionToolCallOutput implements ResponseContract
{
    /**
     * @use ArrayAccessible<FunctionToolCallOutputType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'function_call_output'  $type
     * @param  'in_progress'|'completed'|'incomplete'|null  $status
     * @param  array<int, array<string, mixed>>|string  $output
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $id,
        public readonly array|string $output,
        public readonly string $type,
        public readonly ?string $status,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $createdBy,
    ) {}

    /**
     * @param  FunctionToolCallOutputType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            id: $attributes['id'],
            output: $attributes['output'],
            type: $attributes['type'],
            status: $attributes['status'] ?? null,
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
            'call_id' => $this->callId,
            'id' => $this->id,
            'output' => $this->output,
            'type' => $this->type,
        ];

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
