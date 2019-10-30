<?php

namespace TypedStateMachines;

interface IListener
{
    public function handle(IEvent $event): bool;
}
