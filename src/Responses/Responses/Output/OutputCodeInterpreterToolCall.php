<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Responses\Output\CodeInterpreter\CodeFileOutput;
use OpenAI\Responses\Responses\Output\CodeInterpreter\CodeImageOutput;
use OpenAI\Responses\Responses\Output\CodeInterpreter\CodeTextOutput;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type CodeFileOutputType from CodeFileOutput
 * @phpstan-import-type CodeImageOutputType from CodeImageOutput
 * @phpstan-import-type CodeTextOutputType from CodeTextOutput
 *
 * @phpstan-type OutputType array<int, CodeFileOutputType|CodeImageOutputType|CodeTextOutputType>|null
 * @phpstan-type OutputCodeInterpreterToolCallType array{code?: string|null, id: string, outputs?: OutputType, status: 'in_progress'|'completed'|'incomplete'|'interpreting'|'failed', type: 'code_interpreter_call', container_id: string}
 *
 * @implements ResponseContract<OutputCodeInterpreterToolCallType>
 */
final class OutputCodeInterpreterToolCall implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputCodeInterpreterToolCallType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, CodeFileOutput|CodeImageOutput|CodeTextOutput>|null  $outputs
     * @param  'code_interpreter_call'  $type
     * @param  'in_progress'|'completed'|'incomplete'|'interpreting'|'failed'  $status
     */
    private function __construct(
        public readonly ?string $code,
        public readonly string $id,
        public readonly ?array $outputs,
        public readonly string $status,
        public readonly string $type,
        public readonly string $containerId,
        private readonly bool $hasCode,
        private readonly bool $hasOutputs,
    ) {}

    /**
     * @param  OutputCodeInterpreterToolCallType  $attributes
     */
    public static function from(array $attributes): self
    {
        $outputs = null;

        if (isset($attributes['outputs'])) {
            $outputs = array_map(
                static fn (array $output): CodeFileOutput|CodeImageOutput|CodeTextOutput => match ($output['type']) {
                    'files' => CodeFileOutput::from($output),
                    'image' => CodeImageOutput::from($output),
                    'logs' => CodeTextOutput::from($output),
                },
                $attributes['outputs']
            );
        }

        return new self(
            code: $attributes['code'] ?? null,
            id: $attributes['id'],
            outputs: $outputs,
            status: $attributes['status'],
            type: $attributes['type'],
            containerId: $attributes['container_id'],
            hasCode: array_key_exists('code', $attributes),
            hasOutputs: array_key_exists('outputs', $attributes),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->hasCode) {
            $result['code'] = $this->code;
        }

        $result += [
            'id' => $this->id,
        ];

        if ($this->hasOutputs) {
            $result['outputs'] = $this->outputs !== null
                ? array_map(static fn (CodeFileOutput|CodeImageOutput|CodeTextOutput $output): array => $output->toArray(), $this->outputs)
                : null;
        }

        $result += [
            'status' => $this->status,
            'type' => $this->type,
            'container_id' => $this->containerId,
        ];

        return $result;
    }
}
