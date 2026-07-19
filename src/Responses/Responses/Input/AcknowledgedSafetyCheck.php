<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Input;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type AcknowledgedSafetyCheckType array{code?: string|null, id: string, message?: string|null}
 *
 * @implements ResponseContract<AcknowledgedSafetyCheckType>
 */
final class AcknowledgedSafetyCheck implements ResponseContract
{
    /**
     * @use ArrayAccessible<AcknowledgedSafetyCheckType>
     */
    use ArrayAccessible;

    use Fakeable;

    private function __construct(
        public readonly ?string $code,
        public readonly string $id,
        public readonly ?string $message,
    ) {}

    /**
     * @param  AcknowledgedSafetyCheckType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            code: $attributes['code'] ?? null,
            id: $attributes['id'],
            message: $attributes['message'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = ['id' => $this->id];

        if ($this->code !== null) {
            $result = ['code' => $this->code, ...$result];
        }

        if ($this->message !== null) {
            $result['message'] = $this->message;
        }

        return $result;
    }
}
