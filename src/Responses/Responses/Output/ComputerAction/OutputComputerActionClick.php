<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output\ComputerAction;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type ClickType array{button: 'left'|'right'|'wheel'|'back'|'forward', type: 'click', x: int, y: int, keys?: array<int, string>|null}
 *
 * @implements ResponseContract<ClickType>
 */
final class OutputComputerActionClick implements ResponseContract
{
    /**
     * @use ArrayAccessible<ClickType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'left'|'right'|'wheel'|'back'|'forward'  $button
     * @param  array<int, string>|null  $keys
     * @param  'click'  $type
     */
    private function __construct(
        public readonly string $button,
        public readonly ?array $keys,
        public readonly string $type,
        public readonly int $x,
        public readonly int $y,
    ) {}

    /**
     * @param  ClickType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            button: $attributes['button'],
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
            'button' => $this->button,
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
