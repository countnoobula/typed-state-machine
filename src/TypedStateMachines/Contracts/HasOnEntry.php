<?php

namespace TypedStateMachines\Contracts;

interface HasOnEntry
{

    /**
     * This allows a function to be executed on entry of the state during a transition.
     *
     * @return bool
     */
    public function onEntry();
}
