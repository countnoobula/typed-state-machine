<?php

namespace TypedStateMachines\Actions;

use TypedStateMachines\ActionResult;

class EmptyAction implements Action
{
    /**
     * Evaluate the empty action and return null.
     * This is just a placeholder action.
     *
     * @return ActionResult|null
     */
    public function evaluate(): ?ActionResult
    {
        return null;
    }
}
