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
 * @phpstan-type OutputApplyPatchCallOutputType array{call_id: string, id: string, status: 'completed'|'failed', type: 'apply_patch_call_output', output?: string|null, caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType, created_by?: string|null}
 *
 * @implements ResponseContract<OutputApplyPatchCallOutputType>
 */
final class OutputApplyPatchCallOutput implements ResponseContract
{
    /** @use ArrayAccessible<OutputApplyPatchCallOutputType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'completed'|'failed'  $status
     * @param  'apply_patch_call_output'  $type
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $id,
        public readonly string $status,
        public readonly string $type,
        public readonly ?string $output,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
        public readonly ?string $createdBy,
    ) {}

    /** @param OutputApplyPatchCallOutputType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            id: $attributes['id'],
            status: $attributes['status'],
            type: $attributes['type'],
            output: $attributes['output'] ?? null,
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
            'status' => $this->status,
            'type' => $this->type,
        ];

        if ($this->output !== null) {
            $result['output'] = $this->output;
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
