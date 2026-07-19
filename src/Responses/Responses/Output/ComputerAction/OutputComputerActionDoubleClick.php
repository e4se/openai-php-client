<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output\ComputerAction;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type DoubleClickType array{type: 'double_click', x: float, y: float, keys?: array<int, string>|null}
 *
 * @implements ResponseContract<DoubleClickType>
 */
final class OutputComputerActionDoubleClick implements ResponseContract
{
    /**
     * @use ArrayAccessible<DoubleClickType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, string>|null  $keys
     * @param  'double_click'  $type
     */
    private function __construct(
        public readonly ?array $keys,
        public readonly string $type,
        public readonly float $x,
        public readonly float $y,
    ) {}

    /**
     * @param  DoubleClickType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            keys: $attributes['keys'] ?? null,
            type: $attributes['type'],
            x: $attributes['x'],
            y: $attributes['y'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'type' => $this->type,
            'x' => $this->x,
            'y' => $this->y,
        ];

        if ($this->keys !== null) {
            $result['keys'] = $this->keys;
        }

        return $result;
    }
}
