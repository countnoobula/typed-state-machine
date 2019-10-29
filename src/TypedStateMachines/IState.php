<?php

namespace TypedStateMachines;

interface IState
{
    public function getName(): string;

    public function equals(IState $otherState): bool;

    public function setStateMachine(IStateMachine $stateMachine);
}
