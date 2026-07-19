<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type OutputProgramOutputType array{type: 'program_output', id: string, call_id: string, result: string, status: 'completed'|'incomplete'}
 *
 * @implements ResponseContract<OutputProgramOutputType>
 */
final class OutputProgramOutput implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputProgramOutputType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'program_output'  $type
     * @param  'completed'|'incomplete'  $status
     */
    private function __construct(
        public readonly string $type,
        public readonly string $id,
        public readonly string $callId,
        public readonly string $result,
        public readonly string $status,
    ) {}

    /**
     * @param  OutputProgramOutputType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            id: $attributes['id'],
            callId: $attributes['call_id'],
            result: $attributes['result'],
            status: $attributes['status'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'id' => $this->id,
            'call_id' => $this->callId,
            'result' => $this->result,
            'status' => $this->status,
        ];
    }
}
