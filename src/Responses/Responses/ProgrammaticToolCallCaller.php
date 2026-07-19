<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type ProgrammaticToolCallCallerType array{type: 'program', caller_id: string}
 *
 * @implements ResponseContract<ProgrammaticToolCallCallerType>
 */
final class ProgrammaticToolCallCaller implements ResponseContract
{
    /**
     * @use ArrayAccessible<ProgrammaticToolCallCallerType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'program'  $type
     */
    private function __construct(
        public readonly string $type,
        public readonly string $callerId,
    ) {}

    /**
     * @param  ProgrammaticToolCallCallerType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            callerId: $attributes['caller_id'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'caller_id' => $this->callerId,
        ];
    }
}
