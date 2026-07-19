<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type ShellToolType array{type: 'shell', allowed_callers?: array<int, 'direct'|'programmatic'>, environment?: array<string, mixed>|null}
 *
 * @implements ResponseContract<ShellToolType>
 */
final class ShellTool implements ResponseContract
{
    /** @use ArrayAccessible<ShellToolType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'shell'  $type
     * @param  array<int, 'direct'|'programmatic'>|null  $allowedCallers
     * @param  array<string, mixed>|null  $environment
     */
    private function __construct(
        public readonly string $type,
        public readonly ?array $allowedCallers,
        public readonly ?array $environment,
    ) {}

    /** @param ShellToolType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            allowedCallers: $attributes['allowed_callers'] ?? null,
            environment: $attributes['environment'] ?? null,
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $result = ['type' => $this->type];

        if ($this->allowedCallers !== null) {
            $result['allowed_callers'] = $this->allowedCallers;
        }

        if ($this->environment !== null) {
            $result['environment'] = $this->environment;
        }

        return $result;
    }
}
