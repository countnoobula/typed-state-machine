<?php

namespace TypedStateMachines;

interface IListener
{
    /**
     * Handle the event passed into the Listener.
     *
     * @param IEvent $event
     * @return bool
     */
    public function handle(IEvent $event): bool;
}
