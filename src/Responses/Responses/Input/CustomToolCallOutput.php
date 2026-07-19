<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Input;

use OpenAI\Actions\Responses\ToolCallCallerObjects;
use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Responses\DirectToolCallCaller;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type DirectToolCallCallerType from DirectToolCallCaller
 * @phpstan-import-type ProgrammaticToolCallCallerType from ProgrammaticToolCallCaller
 *
 * @phpstan-type CustomToolCallOutputType array{call_id: string, output: string|array<int, array<string, mixed>>, type: 'custom_tool_call_output', id?: string, caller?: DirectToolCallCallerType|ProgrammaticToolCallCallerType}
 *
 * @implements ResponseContract<CustomToolCallOutputType>
 */
final class CustomToolCallOutput implements ResponseContract
{
    /**
     * @use ArrayAccessible<CustomToolCallOutputType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'custom_tool_call_output'  $type
     * @param  array<int, array<string, mixed>>|string  $output
     */
    private function __construct(
        public readonly string $callId,
        public readonly array|string $output,
        public readonly string $type,
        public readonly ?string $id,
        public readonly DirectToolCallCaller|ProgrammaticToolCallCaller|null $caller,
    ) {}

    /**
     * @param  CustomToolCallOutputType  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            callId: $attributes['call_id'],
            output: $attributes['output'],
            type: $attributes['type'],
            id: $attributes['id'] ?? null,
            caller: isset($attributes['caller'])
                ? ToolCallCallerObjects::parse($attributes['caller'])
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
            'call_id' => $this->callId,
            'output' => $this->output,
        ];

        if ($this->id !== null) {
            $result = [
                'type' => $this->type,
                'call_id' => $this->callId,
                'id' => $this->id,
                'output' => $this->output,
            ];
        }

        if ($this->caller !== null) {
            $result['caller'] = $this->caller->toArray();
        }

        return $result;
    }
}
