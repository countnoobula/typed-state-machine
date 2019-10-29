<?php

namespace TypedStateMachines\Actions;

use TypedStateMachines\ActionResult;

class EmptyAction implements Action
{
    public function evaluate(): ?ActionResult
    {
        return null;
    }
}
