<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type McpToolNamesFilterType array{tool_names?: array<int, string>|null, read_only?: bool|null}
 *
 * @implements ResponseContract<McpToolNamesFilterType>
 */
final class McpToolNamesFilter implements ResponseContract
{
    /**
     * @use ArrayAccessible<McpToolNamesFilterType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, string>|null  $toolNames
     */
    private function __construct(
        public readonly ?array $toolNames,
        public readonly ?bool $readOnly,
    ) {}

    /**
     * @param  McpToolNamesFilterType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            toolNames: $attributes['tool_names'] ?? null,
            readOnly: $attributes['read_only'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            'tool_names' => $this->toolNames,
            'read_only' => $this->readOnly,
        ], fn (mixed $value): bool => $value !== null);
    }
}
