<?php

namespace TypedStateMachines;

interface IEvent
{
    public function getName(): string;
}
