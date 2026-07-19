<?php

declare(strict_types=1);

namespace OpenAI\Responses\Responses\Output;

use OpenAI\Contracts\ResponseContract;
use OpenAI\Responses\Concerns\ArrayAccessible;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionClick as Click;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionDoubleClick as DoubleClick;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionDrag as Drag;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionKeyPress as KeyPress;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionMove as Move;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionScreenshot as Screenshot;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionScroll as Scroll;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionType as Type;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerActionWait as Wait;
use OpenAI\Responses\Responses\Output\ComputerAction\OutputComputerPendingSafetyCheck;
use OpenAI\Testing\Responses\Concerns\Fakeable;

/**
 * @phpstan-import-type ClickType from Click
 * @phpstan-import-type DoubleClickType from DoubleClick
 * @phpstan-import-type DragType from Drag
 * @phpstan-import-type KeyPressType from KeyPress
 * @phpstan-import-type MoveType from Move
 * @phpstan-import-type ScreenshotType from Screenshot
 * @phpstan-import-type ScrollType from Scroll
 * @phpstan-import-type TypeType from Type
 * @phpstan-import-type WaitType from Wait
 * @phpstan-import-type PendingSafetyCheckType from OutputComputerPendingSafetyCheck
 *
 * @phpstan-type OutputComputerActionType ClickType|DoubleClickType|DragType|KeyPressType|MoveType|ScreenshotType|ScrollType|TypeType|WaitType
 * @phpstan-type OutputComputerToolCallType array{action?: OutputComputerActionType, actions?: array<int, OutputComputerActionType>, call_id: string, id?: string, pending_safety_checks?: array<int, PendingSafetyCheckType>, status: 'in_progress'|'completed'|'incomplete', type: 'computer_call'}
 *
 * @implements ResponseContract<OutputComputerToolCallType>
 */
final class OutputComputerToolCall implements ResponseContract
{
    /**
     * @use ArrayAccessible<OutputComputerToolCallType>
     */
    use ArrayAccessible;

    use Fakeable;

    /**
     * @param  array<int, Click|DoubleClick|Drag|KeyPress|Move|Screenshot|Scroll|Type|Wait>|null  $actions
     * @param  array<int, OutputComputerPendingSafetyCheck>|null  $pendingSafetyChecks
     * @param  'in_progress'|'completed'|'incomplete'  $status
     * @param  'computer_call'  $type
     */
    private function __construct(
        public readonly Click|DoubleClick|Drag|KeyPress|Move|Screenshot|Scroll|Type|Wait|null $action,
        public readonly ?array $actions,
        public readonly string $callId,
        public readonly ?string $id,
        public readonly ?array $pendingSafetyChecks,
        public readonly string $status,
        public readonly string $type,
    ) {}

    /**
     * @param  OutputComputerToolCallType  $attributes
     */
    public static function from(array $attributes): self
    {
        $action = isset($attributes['action'])
            ? self::parseAction($attributes['action'])
            : null;

        $actions = isset($attributes['actions'])
            ? array_map(self::parseAction(...), $attributes['actions'])
            : null;

        $pendingSafetyChecks = isset($attributes['pending_safety_checks'])
            ? array_map(
                fn (array $safetyCheck): OutputComputerPendingSafetyCheck => OutputComputerPendingSafetyCheck::from($safetyCheck),
                $attributes['pending_safety_checks']
            )
            : null;

        return new self(
            action: $action,
            actions: $actions,
            callId: $attributes['call_id'],
            id: $attributes['id'] ?? null,
            pendingSafetyChecks: $pendingSafetyChecks,
            status: $attributes['status'],
            type: $attributes['type'],
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
        ];

        if ($this->id !== null) {
            $result['id'] = $this->id;
        }

        if ($this->action !== null) {
            $result['action'] = $this->action->toArray();
        }

        if ($this->actions !== null) {
            $result['actions'] = array_map(
                fn (Click|DoubleClick|Drag|KeyPress|Move|Screenshot|Scroll|Type|Wait $action): array => $action->toArray(),
                $this->actions,
            );
        }

        if ($this->pendingSafetyChecks !== null) {
            $result['pending_safety_checks'] = array_map(
                fn (OutputComputerPendingSafetyCheck $safetyCheck): array => $safetyCheck->toArray(),
                $this->pendingSafetyChecks,
            );
        }

        $result['status'] = $this->status;

        return $result;
    }

    /**
     * @param  OutputComputerActionType  $action
     */
    private static function parseAction(array $action): Click|DoubleClick|Drag|KeyPress|Move|Screenshot|Scroll|Type|Wait
    {
        return match ($action['type']) {
            'click' => Click::from($action),
            'double_click' => DoubleClick::from($action),
            'drag' => Drag::from($action),
            'keypress' => KeyPress::from($action),
            'move' => Move::from($action),
            'screenshot' => Screenshot::from($action),
            'scroll' => Scroll::from($action),
            'type' => Type::from($action),
            'wait' => Wait::from($action),
        };
    }
}
