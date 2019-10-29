<?php

namespace TypedStateMachines\Contracts;

interface HasOnExit
{

    /**
     * This allows a function to be executed on exit of the state during a transition.
     *
     * @return bool
     */
    public function onExit();
}
