<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Input;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type ComputerToolCallOutputScreenshotType from ComputerToolCallOutputScreenshot
 * @phpstan-import-type AcknowledgedSafetyCheckType from AcknowledgedSafetyCheck
 *
 * @phpstan-type ComputerToolCallOutputType array{call_id: string, id: string, output: ComputerToolCallOutputScreenshotType, type: 'computer_call_output', acknowledged_safety_checks?: array<int, AcknowledgedSafetyCheckType>|null, status: 'in_progress'|'completed'|'incomplete'|'failed', created_by?: string|null}
 *
 * @implements ResponseContract<ComputerToolCallOutputType>
 */
final class ComputerToolCallOutput implements ResponseContract
{
    /**
     * @use ArrayAccessible<ComputerToolCallOutputType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'computer_call_output'  $type
     * @param  array<int, AcknowledgedSafetyCheck>|null  $acknowledgedSafetyChecks
     * @param  'in_progress'|'completed'|'incomplete'|'failed'  $status
     */
    private function __construct(
        public readonly string $callId,
        public readonly string $id,
        public readonly ComputerToolCallOutputScreenshot $output,
        public readonly string $type,
        public readonly ?array $acknowledgedSafetyChecks,
        public readonly string $status,
        public readonly ?string $createdBy,
    ) {}

    /**
     * @param  ComputerToolCallOutputType  $attributes
     */
    public static function from(array $attributes): self
    {
        $acknowledgedSafetyChecks = isset($attributes['acknowledged_safety_checks'])
            ? array_map(
                fn (array $acknowledgedSafetyCheck) => AcknowledgedSafetyCheck::from($acknowledgedSafetyCheck),
                $attributes['acknowledged_safety_checks'],
            )
            : null;

        return new self(
            callId: $attributes['call_id'],
            id: $attributes['id'],
            output: ComputerToolCallOutputScreenshot::from($attributes['output']),
            type: $attributes['type'],
            acknowledgedSafetyChecks: $acknowledgedSafetyChecks,
            status: $attributes['status'],
            createdBy: $attributes['created_by'] ?? null,
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [
            'call_id' => $this->callId,
            'id' => $this->id,
            'output' => $this->output->toArray(),
            'type' => $this->type,
            'status' => $this->status,
        ];

        if ($this->acknowledgedSafetyChecks !== null) {
            $result['acknowledged_safety_checks'] = array_map(
                fn (AcknowledgedSafetyCheck $acknowledgedSafetyCheck) => $acknowledgedSafetyCheck->toArray(),
                $this->acknowledgedSafetyChecks,
            );
        }

        if ($this->createdBy !== null) {
            $result['created_by'] = $this->createdBy;
        }

        return $result;
    }
}
