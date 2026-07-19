<?php

declare(strict_types=1);

namespace OpenAI\Actions\Responses;

use OpenAI\Responses\Responses\DirectToolCallCaller;
use OpenAI\Responses\Responses\ProgrammaticToolCallCaller;

/**
 * @phpstan-import-type DirectToolCallCallerType from DirectToolCallCaller
 * @phpstan-import-type ProgrammaticToolCallCallerType from ProgrammaticToolCallCaller
 *
 * @phpstan-type ResponseToolCallCallerObjectTypes DirectToolCallCallerType|ProgrammaticToolCallCallerType
 * @phpstan-type ResponseToolCallCallerObjectReturnType DirectToolCallCaller|ProgrammaticToolCallCaller
 */
final class ToolCallCallerObjects
{
    /**
     * @param  ResponseToolCallCallerObjectTypes  $caller
     * @return ResponseToolCallCallerObjectReturnType
     */
    public static function parse(array $caller): DirectToolCallCaller|ProgrammaticToolCallCaller
    {
        return match ($caller['type']) {
            'direct' => DirectToolCallCaller::from($caller),
            'program' => ProgrammaticToolCallCaller::from($caller),
        };
    }
}
