<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output\ComputerAction;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type DragPathType from OutputComputerDragPath
 *
 * @phpstan-type DragType array{path: array<int, DragPathType>, type: 'drag', keys?: array<int, string>|null}
 *
 * @implements ResponseContract<DragType>
 */
final class OutputComputerActionDrag implements ResponseContract
{
    /**
     * @use ArrayAccessible<DragType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, OutputComputerDragPath>  $path
     * @param  array<int, string>|null  $keys
     * @param  'drag'  $type
     */
    private function __construct(
        public readonly array $path,
        public readonly ?array $keys,
        public readonly string $type,
    ) {}

    /**
     * @param  DragType  $attributes
     */
    public static function from(array $attributes): self
    {
        $paths = array_map(
            static fn (array $path): OutputComputerDragPath => OutputComputerDragPath::from($path),
            $attributes['path'],
        );

        return new self(
            path: $paths,
            keys: $attributes['keys'] ?? null,
            type: $attributes['type'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'path' => array_map(
                static fn (OutputComputerDragPath $path): array => $path->toArray(),
                $this->path,
            ),
            'type' => $this->type,
        ];

        if ($this->keys !== null) {
            $result['keys'] = $this->keys;
        }

        return $result;
    }
}
