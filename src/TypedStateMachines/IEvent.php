<?php

namespace TypedStateMachines;

interface IEvent
{
    /**
     * Fetch the name of the Event.
     *
     * @return string
     */
    public function getName(): string;
}
