<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Tool;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-type CodeInterpreterContainerAutoNetworkPolicyDisabledType array{type: 'disabled'}
 * @phpstan-type CodeInterpreterContainerAutoNetworkPolicyDomainSecretType array{domain: string, name: string, value: string}
 * @phpstan-type CodeInterpreterContainerAutoNetworkPolicyAllowlistType array{allowed_domains: array<int, string>, type: 'allowlist', domain_secrets?: array<int, CodeInterpreterContainerAutoNetworkPolicyDomainSecretType>|null}
 * @phpstan-type CodeInterpreterContainerAutoNetworkPolicyType CodeInterpreterContainerAutoNetworkPolicyDisabledType|CodeInterpreterContainerAutoNetworkPolicyAllowlistType
 * @phpstan-type CodeInterpreterContainerAutoType array{file_ids?: array<int, string>|null, type: 'auto', memory_limit?: '1g'|'4g'|'16g'|'64g'|null, network_policy?: CodeInterpreterContainerAutoNetworkPolicyType|null}
 *
 * @implements ResponseContract<CodeInterpreterContainerAutoType>
 */
final class CodeInterpreterContainerAuto implements ResponseContract
{
    /**
     * @use ArrayAccessible<CodeInterpreterContainerAutoType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, string>|null  $fileIds
     * @param  'auto'  $type
     * @param  '1g'|'4g'|'16g'|'64g'|null  $memoryLimit
     * @param  CodeInterpreterContainerAutoNetworkPolicyType|null  $networkPolicy
     */
    private function __construct(
        public readonly ?array $fileIds,
        public readonly string $type,
        public readonly ?string $memoryLimit,
        public readonly ?array $networkPolicy,
    ) {}

    /**
     * @param  CodeInterpreterContainerAutoType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            fileIds: $attributes['file_ids'] ?? null,
            type: $attributes['type'],
            memoryLimit: $attributes['memory_limit'] ?? null,
            networkPolicy: $attributes['network_policy'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'file_ids' => $this->fileIds,
            'type' => $this->type,
        ];

        if ($this->memoryLimit !== null) {
            $result['memory_limit'] = $this->memoryLimit;
        }

        if ($this->networkPolicy !== null) {
            $result['network_policy'] = $this->networkPolicy;
        }

        return $result;
    }
}
