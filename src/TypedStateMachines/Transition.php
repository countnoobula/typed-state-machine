<?php

namespace TypedStateMachines;

use TypedStateMachines\Actions\Action;
use TypedStateMachines\Actions\EmptyAction;
use TypedStateMachines\Conditions\TrueCondition;
use TypedStateMachines\Conditions\Condition;

abstract class Transition implements ITransition
{

    abstract public function getName(): string;

    public function getAction(): Action
    {
        return new EmptyAction();
    }

    public function getCondition(): Condition
    {
        return new TrueCondition();
    }

    public function equals(ITransition $otherTransition): bool
    {
        return get_class($otherTransition) === get_class($this)
            && $this->getName() === $otherTransition->getName();
    }

    public function setStateMachine(IStateMachine $stateMachine)
    {
    }
}
