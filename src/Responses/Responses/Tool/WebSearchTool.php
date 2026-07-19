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
 * @phpstan-type WebSearchToolType array{type: 'web_search'|'web_search_2025_08_26', filters?: WebSearchFiltersType|null, search_context_size?: 'low'|'medium'|'high'|null, user_location?: UserLocationType|null}
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
     * @param  'web_search'|'web_search_2025_08_26'  $type
     * @param  WebSearchFiltersType|null  $filters
     * @param  'low'|'medium'|'high'|null  $searchContextSize
     */
    private function __construct(
        public readonly string $type,
        public readonly ?array $filters,
        public readonly ?string $searchContextSize,
        public readonly ?WebSearchUserLocation $userLocation,
    ) {}

    /**
     * @param  WebSearchToolType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            filters: $attributes['filters'] ?? null,
            searchContextSize: $attributes['search_context_size'] ?? null,
            userLocation: isset($attributes['user_location'])
                ? WebSearchUserLocation::from($attributes['user_location'])
                : null,
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

        if ($this->filters !== null) {
            $result['filters'] = $this->filters;
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
