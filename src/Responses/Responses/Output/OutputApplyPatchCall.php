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
 * @phpstan-type OutputApplyPatchCallOperationType array{type: 'create_file', diff: string, path: string}|array{type: 'delete_file', path: string}|array{type: 'update_file', diff: string, path: string}
 * @phpstan-type OutputApplyPatchCallType array{call_id: string, id: string, operation: OutputApplyPatchCallOperationType, status: 'in_progress'|'completed', type: 'apply_patch_call', caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, created_by?: string|null}
 *
 * @implements ResponseContract<OutputApplyPatchCallType>
 */
final class OutputApplyPatchCall implements ResponseContract
{
    /** @use ArrayAccessible<OutputApplyPatchCallType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  OutputApplyPatchCallOperationType  $operation
     * @param  'in_progress'|'completed'  $status
     * @param  'apply_patch_call'  $type
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $id,
        public readonly array $operation,
        public readonly string $status,
        public readonly string $type,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $createdBy,
    ) {}

    /** @param OutputApplyPatchCallType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            id: $attributes['id'],
            operation: $attributes['operation'],
            status: $attributes['status'],
            type: $attributes['type'],
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
            'operation' => $this->operation,
            'status' => $this->status,
            'type' => $this->type,
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
