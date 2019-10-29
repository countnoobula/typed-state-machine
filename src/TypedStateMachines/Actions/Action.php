<?php

namespace TypedStateMachines\Actions;

use TypedStateMachines\ActionResult;

interface Action
{
    public function evaluate(): ?ActionResult;
}
