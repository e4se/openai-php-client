<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Input;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type ComputerToolCallOutputScreenshotType array{type: 'computer_screenshot', file_id?: string|null, image_url?: string|null}
 *
 * @implements ResponseContract<ComputerToolCallOutputScreenshotType>
 */
final class ComputerToolCallOutputScreenshot implements ResponseContract
{
    /**
     * @use ArrayAccessible<ComputerToolCallOutputScreenshotType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'computer_screenshot'  $type
     */
    private function __construct(
        public readonly string $type,
        public readonly ?string $fileId,
        public readonly ?string $imageUrl,
    ) {}

    /**
     * @param  ComputerToolCallOutputScreenshotType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            fileId: $attributes['file_id'] ?? null,
            imageUrl: $attributes['image_url'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = ['type' => $this->type];

        if ($this->fileId !== null) {
            $result['file_id'] = $this->fileId;
        }

        if ($this->imageUrl !== null) {
            $result['image_url'] = $this->imageUrl;
        }

        return $result;
    }
}
