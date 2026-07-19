<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type PreviewUserLocationType array{type: 'approximate', city?: string|null, country?: string|null, region?: string|null, timezone?: string|null}
 *
 * @implements ResponseContract<PreviewUserLocationType>
 */
final class WebSearchPreviewUserLocation implements ResponseContract
{
    /** @use ArrayAccessible<PreviewUserLocationType> */
    use ArrayAccessible;

    use Fakeable;

    /** @param 'approximate' $type */
    private function __construct(
        public readonly string $type,
        public readonly ?string $city,
        public readonly ?string $country,
        public readonly ?string $region,
        public readonly ?string $timezone,
    ) {}

    /** @param PreviewUserLocationType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'],
            city: $attributes['city'] ?? null,
            country: $attributes['country'] ?? null,
            region: $attributes['region'] ?? null,
            timezone: $attributes['timezone'] ?? null,
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'city' => $this->city,
            'country' => $this->country,
            'region' => $this->region,
            'timezone' => $this->timezone,
        ], fn (mixed $value): bool => $value !== null);
    }
}
