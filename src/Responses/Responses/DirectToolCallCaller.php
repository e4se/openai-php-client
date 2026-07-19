<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type DirectToolCallCallerType array{type: 'direct'}
 *
 * @implements ResponseContract<DirectToolCallCallerType>
 */
final class DirectToolCallCaller implements ResponseContract
{
    /**
     * @use ArrayAccessible<DirectToolCallCallerType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'direct'  $type
     */
    private function __construct(
        public readonly string $type,
    ) {}

    /**
     * @param  DirectToolCallCallerType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
        ];
    }
}
