<?php

namespace TypedStateMachines\Contracts;

interface HasOnExit
{

    /**
     * This allows a function to be executed on exit of the State during a Transition.
     *
     * @return bool
     */
    public function onExit();
}
