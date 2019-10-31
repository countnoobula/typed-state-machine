<?php

namespace TypedStateMachines\Actions;

use TypedStateMachines\ActionResult;

interface Action
{
    /**
     * Evaluate the Action and return an ActionResult or null.
     *
     * @return ActionResult|null
     */
    public function evaluate(): ?ActionResult;
}
