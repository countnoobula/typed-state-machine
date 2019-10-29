<?php

namespace TypedStateMachines;

use TypedStateMachines\Actions\Action;
use TypedStateMachines\Conditions\Condition;

interface ITransition
{
    public function getName(): string;

    public function getAction(): Action;

    public function getCondition(): Condition;

    public function equals(ITransition $otherTransition): bool;

    public function setStateMachine(IStateMachine $stateMachine);
}
