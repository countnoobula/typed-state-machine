<?php

namespace TypedStateMachines;

abstract class State implements IState
{
    public function getName(): string
    {
        return static::class;
    }

    public function equals(IState $otherState): bool
    {
        return get_class($this) === get_class($otherState)
            && $this->getName() === $otherState->getName();
    }

    public function setStateMachine(IStateMachine $stateMachine)
    {
    }
}
