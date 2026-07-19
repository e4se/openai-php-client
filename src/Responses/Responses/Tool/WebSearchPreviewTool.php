<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type PreviewUserLocationType from WebSearchPreviewUserLocation
 *
 * @phpstan-type WebSearchPreviewToolType array{type: 'web_search_preview'|'web_search_preview_2025_03_11', search_content_types?: array<int, 'text'|'image'>|null, search_context_size?: 'low'|'medium'|'high'|null, user_location?: PreviewUserLocationType|null}
 *
 * @implements ResponseContract<WebSearchPreviewToolType>
 */
final class WebSearchPreviewTool implements ResponseContract
{
    /** @use ArrayAccessible<WebSearchPreviewToolType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'web_search_preview'|'web_search_preview_2025_03_11'  $type
     * @param  array<int, 'text'|'image'>|null  $searchContentTypes
     * @param  'low'|'medium'|'high'|null  $searchContextSize
     */
    private function __construct(
        public readonly string $type,
        public readonly ?array $searchContentTypes,
        public readonly ?string $searchContextSize,
        public readonly ?WebSearchPreviewUserLocation $userLocation,
    ) {}

    /** @param WebSearchPreviewToolType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            searchContentTypes: $attributes['search_content_types'] ?? null,
            searchContextSize: $attributes['search_context_size'] ?? null,
            userLocation: isset($attributes['user_location'])
                ? WebSearchPreviewUserLocation::from($attributes['user_location'])
                : null,
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        $result = ['type' => $this->type];

        if ($this->searchContentTypes !== null) {
            $result['search_content_types'] = $this->searchContentTypes;
        }

        if ($this->searchContextSize !== null) {
            $result['search_context_size'] = $this->searchContextSize;
        }

        if ($this->userLocation !== null) {
            $result['user_location'] = $this->userLocation->toArray();
        }

        return $result;
    }
}
