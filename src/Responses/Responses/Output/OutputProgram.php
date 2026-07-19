<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type OutputProgramType array{type: 'program', id: string, call_id: string, code: string, fingerprint: string}
 *
 * @implements ResponseContract<OutputProgramType>
 */
final class OutputProgram implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputProgramType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'program'  $type
     */
    private function __construct(
        public readonly string $type,
        public readonly string $id,
        public readonly string $callId,
        public readonly string $code,
        public readonly string $fingerprint,
    ) {}

    /**
     * @param  OutputProgramType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            id: $attributes['id'],
            callId: $attributes['call_id'],
            code: $attributes['code'],
            fingerprint: $attributes['fingerprint'],
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
            'code' => $this->code,
            'fingerprint' => $this->fingerprint,
        ];
    }
}
