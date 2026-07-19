<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Actions\Responses\ToolObjects;
use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Responses\Tool\ApplyPatchTool;
use OpenAI\Responses\Responses\Tool\CodeInterpreterTool;
use OpenAI\Responses\Responses\Tool\ComputerTool;
use OpenAI\Responses\Responses\Tool\ComputerUseTool;
use OpenAI\Responses\Responses\Tool\CustomTool;
use OpenAI\Responses\Responses\Tool\FileSearchTool;
use OpenAI\Responses\Responses\Tool\FunctionTool;
use OpenAI\Responses\Responses\Tool\ImageGenerationTool;
use OpenAI\Responses\Responses\Tool\LocalShellTool;
use OpenAI\Responses\Responses\Tool\NamespaceTool;
use OpenAI\Responses\Responses\Tool\ProgrammaticToolCallingTool;
use OpenAI\Responses\Responses\Tool\RemoteMcpTool;
use OpenAI\Responses\Responses\Tool\ShellTool;
use OpenAI\Responses\Responses\Tool\ToolSearchTool;
use OpenAI\Responses\Responses\Tool\WebSearchPreviewTool;
use OpenAI\Responses\Responses\Tool\WebSearchTool;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type ResponseToolObjectTypes from ToolObjects
 * @phpstan-import-type ResponseToolObjectReturnType from ToolObjects
 *
 * @phpstan-type OutputAdditionalToolsType array{id: string, role: 'unknown'|'user'|'assistant'|'system'|'critic'|'discriminator'|'developer'|'tool', tools: ResponseToolObjectTypes, type: 'additional_tools'}
 *
 * @implements ResponseContract<OutputAdditionalToolsType>
 */
final class OutputAdditionalTools implements ResponseContract
{
    /** @use ArrayAccessible<OutputAdditionalToolsType> */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  'unknown'|'user'|'assistant'|'system'|'critic'|'discriminator'|'developer'|'tool'  $role
     * @param  ResponseToolObjectReturnType  $tools
     * @param  'additional_tools'  $type
     */
    private function __construct(
        public readonly string $id,
        public readonly string $role,
        public readonly array $tools,
        public readonly string $type,
    ) {}

    /** @param OutputAdditionalToolsType $attributes */
    public static function from(array $attributes): self
    {
        return new self(
            id: $attributes['id'],
            role: $attributes['role'],
            tools: ToolObjects::parse($attributes['tools']),
            type: $attributes['type'],
        );
    }

    /** {@inheritDoc} */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'tools' => array_map(
                fn (ApplyPatchTool|CodeInterpreterTool|ComputerTool|ComputerUseTool|CustomTool|FileSearchTool|FunctionTool|ImageGenerationTool|LocalShellTool|NamespaceTool|ProgrammaticToolCallingTool|RemoteMcpTool|ShellTool|ToolSearchTool|WebSearchPreviewTool|WebSearchTool $tool): array => $tool->toArray(),
                $this->tools,
            ),
            'type' => $this->type,
        ];
    }
}
