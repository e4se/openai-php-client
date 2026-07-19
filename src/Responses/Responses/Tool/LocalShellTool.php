<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type LocalShellToolType array{type: 'local_shell', allowed_callers?: array<int, 'direct'|'programmatic'>}
 *
 * @implements ResponseContract<LocalShellToolType>
 */
final class LocalShellTool implements ResponseContract
{
    /** @use ArrayAccessible<LocalShellToolType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'local_shell'  $type
     * @param  array<int, 'direct'|'programmatic'>|null  $allowedCallers
     */
    private function __construct(
        public readonly string $type,
        public readonly ?array $allowedCallers,
    ) {}

    /** @param LocalShellToolType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            allowedCallers: $attributes['allowed_callers'] ?? null,
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $result = ['type' => $this->type];

        if ($this->allowedCallers !== null) {
            $result['allowed_callers'] = $this->allowedCallers;
        }

        return $result;
    }
}
