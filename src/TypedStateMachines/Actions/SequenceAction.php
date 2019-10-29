<?php

namespace TypedStateMachines\Actions;

use TypedStateMachines\ActionResult;

class SequenceAction implements Action
{
    /**
     * @var Action[]
     */
    private $actions;

    public function __construct(Action ...$actions)
    {
        $this->actions = $actions;
    }

    public function evaluate(): ?ActionResult
    {
        foreach ($this->actions as $action) {
            $action->evaluate();
        }

        return null;
    }
}
