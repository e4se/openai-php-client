<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type UserLocationType array{type?: 'approximate'|null, city?: string|null, country?: string|null, region?: string|null, timezone?: string|null}
 *
 * @implements ResponseContract<UserLocationType>
 */
final class WebSearchUserLocation implements ResponseContract
{
    /**
     * @use ArrayAccessible<UserLocationType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'approximate'|null  $type
     */
    private function __construct(
        public readonly ?string $type,
        public readonly ?string $city,
        public readonly ?string $country,
        public readonly ?string $region,
        public readonly ?string $timezone,
    ) {}

    /**
     * @param  UserLocationType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            type: $attributes['type'] ?? null,
            city: $attributes['city'] ?? null,
            country: $attributes['country'] ?? null,
            region: $attributes['region'] ?? null,
            timezone: $attributes['timezone'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->type !== null) {
            $result['type'] = $this->type;
        }

        if ($this->city !== null) {
            $result['city'] = $this->city;
        }

        if ($this->country !== null) {
            $result['country'] = $this->country;
        }

        if ($this->region !== null) {
            $result['region'] = $this->region;
        }

        if ($this->timezone !== null) {
            $result['timezone'] = $this->timezone;
        }

        return $result;
    }
}
