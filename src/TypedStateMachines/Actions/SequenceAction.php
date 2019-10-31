<?php

namespace TypedStateMachines\Actions;

use TypedStateMachines\ActionResult;

class SequenceAction implements Action
{
    /**
     * @var Action[]
     */
    private $actions;

    /**
     * Construct a SequenceAction of Actions to process in sequence.
     * @param Action ...$actions
     */
    public function __construct(Action ...$actions)
    {
        $this->actions = $actions;
    }

    /**
     * Evaluate the Actions and return null.
     *
     * @return ActionResult|null
     */
    public function evaluate(): ?ActionResult
    {
        foreach ($this->actions as $action) {
            $action->evaluate();
        }

        return null;
    }
}
