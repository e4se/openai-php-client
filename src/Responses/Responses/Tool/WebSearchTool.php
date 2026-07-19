<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type UserLocationType from WebSearchUserLocation
 *
 * @phpstan-type WebSearchFiltersType array{allowed_domains?: array<int, string>|null}
 * @phpstan-type WebSearchToolType array{type: 'web_search'|'web_search_2025_08_26'|'web_search_preview'|'web_search_preview_2025_03_11', filters?: WebSearchFiltersType|null, search_content_types?: array<int, 'text'|'image'>|null, search_context_size?: 'low'|'medium'|'high'|null, user_location?: UserLocationType|null}
 *
 * @implements ResponseContract<WebSearchToolType>
 */
final class WebSearchTool implements ResponseContract
{
    /**
     * @use ArrayAccessible<WebSearchToolType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'web_search'|'web_search_2025_08_26'|'web_search_preview'|'web_search_preview_2025_03_11'  $type
     * @param  WebSearchFiltersType|null  $filters
     * @param  array<int, 'text'|'image'>|null  $searchContentTypes
     * @param  'low'|'medium'|'high'|null  $searchContextSize
     */
    private function __construct(
        public readonly string $type,
        public readonly ?array $filters,
        public readonly ?array $searchContentTypes,
        public readonly ?string $searchContextSize,
        public readonly ?WebSearchUserLocation $userLocation,
        private readonly bool $hasFilters,
        private readonly bool $hasSearchContentTypes,
        private readonly bool $hasSearchContextSize,
        private readonly bool $hasUserLocation,
    ) {}

    /**
     * @param  WebSearchToolType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            filters: $attributes['filters'] ?? null,
            searchContentTypes: $attributes['search_content_types'] ?? null,
            searchContextSize: $attributes['search_context_size'] ?? null,
            userLocation: isset($attributes['user_location'])
                ? WebSearchUserLocation::from($attributes['user_location'])
                : null,
            hasFilters: array_key_exists('filters', $attributes),
            hasSearchContentTypes: array_key_exists('search_content_types', $attributes),
            hasSearchContextSize: array_key_exists('search_context_size', $attributes),
            hasUserLocation: array_key_exists('user_location', $attributes),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'type' => $this->type,
        ];

        if ($this->hasFilters) {
            $result['filters'] = $this->filters;
        }

        if ($this->hasSearchContentTypes) {
            $result['search_content_types'] = $this->searchContentTypes;
        }

        if ($this->hasSearchContextSize) {
            $result['search_context_size'] = $this->searchContextSize;
        }

        if ($this->hasUserLocation) {
            $result['user_location'] = $this->userLocation?->toArray();
        }

        return $result;
    }
}
