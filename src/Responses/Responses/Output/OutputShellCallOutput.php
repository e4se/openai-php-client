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
 * @phpstan-type OutputShellCallOutputContentType array{outcome: array{type: 'timeout'}|array{type: 'exit', exit_code: int}, stderr: string, stdout: string, created_by?: string|null}
 * @phpstan-type OutputShellCallOutputType array{call_id: string, id: string, output: array<int, OutputShellCallOutputContentType>, status: 'in_progress'|'completed'|'incomplete', type: 'shell_call_output', max_output_length?: int|null, caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, created_by?: string|null}
 *
 * @implements ResponseContract<OutputShellCallOutputType>
 */
final class OutputShellCallOutput implements ResponseContract
{
    /** @use ArrayAccessible<OutputShellCallOutputType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, OutputShellCallOutputContentType>  $output
     * @param  'in_progress'|'completed'|'incomplete'  $status
     * @param  'shell_call_output'  $type
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $id,
        public readonly array $output,
        public readonly string $status,
        public readonly string $type,
        public readonly ?int $maxOutputLength,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $createdBy,
    ) {}

    /** @param OutputShellCallOutputType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            id: $attributes['id'],
            output: $attributes['output'],
            status: $attributes['status'],
            type: $attributes['type'],
            maxOutputLength: $attributes['max_output_length'] ?? null,
            caller: isset($attributes['caller'])
                ? ToolCallCallerObjects::parse($attributes['caller'])
                : null,
            createdBy: $attributes['created_by'] ?? null,
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $result = [
            'call_id' => $this->callId,
            'id' => $this->id,
            'output' => $this->output,
            'status' => $this->status,
            'type' => $this->type,
        ];

        if ($this->maxOutputLength !== null) {
            $result['max_output_length'] = $this->maxOutputLength;
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
