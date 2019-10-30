<?php

namespace TypedStateMachines\Contracts;

interface HasOnEntry
{

    /**
     * This allows a function to be executed on entry of the State during a Transition.
     *
     * @return bool
     */
    public function onEntry();
}
