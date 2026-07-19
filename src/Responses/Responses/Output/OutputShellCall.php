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
 * @phpstan-type OutputShellCallActionType array{commands: array<int, string>, max_output_length: int|null, timeout_ms: int|null}
 * @phpstan-type OutputShellCallType array{action: OutputShellCallActionType, call_id: string, id: string, status: 'in_progress'|'completed'|'incomplete', type: 'shell_call', environment?: array<string, mixed>|null, caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, created_by?: string|null}
 *
 * @implements ResponseContract<OutputShellCallType>
 */
final class OutputShellCall implements ResponseContract
{
    /** @use ArrayAccessible<OutputShellCallType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  OutputShellCallActionType  $action
     * @param  'in_progress'|'completed'|'incomplete'  $status
     * @param  'shell_call'  $type
     * @param  array<string, mixed>|null  $environment
     */
    private function __construct(
        public readonly array $action,
        public readonly string $callId,
        public readonly string $id,
        public readonly string $status,
        public readonly string $type,
        public readonly ?array $environment,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $createdBy,
        private readonly bool $hasEnvironment,
    ) {}

    /** @param OutputShellCallType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            action: $attributes['action'],
            callId: $attributes['call_id'],
            id: $attributes['id'],
            status: $attributes['status'],
            type: $attributes['type'],
            environment: $attributes['environment'] ?? null,
            caller: isset($attributes['caller'])
                ? ToolCallCallerObjects::parse($attributes['caller'])
                : null,
            createdBy: $attributes['created_by'] ?? null,
            hasEnvironment: array_key_exists('environment', $attributes),
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $result = [
            'action' => $this->action,
            'call_id' => $this->callId,
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type,
        ];

        if ($this->hasEnvironment) {
            $result['environment'] = $this->environment;
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
