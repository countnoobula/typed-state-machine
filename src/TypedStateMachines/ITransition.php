<?php

namespace TypedStateMachines;

use TypedStateMachines\Actions\Action;
use TypedStateMachines\Conditions\Condition;

interface ITransition
{
    /**
     * Fetch the name of the Transition.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Fetch the Action to evaluate when the Transition is triggered.
     *
     * @return Action
     */
    public function getAction(): Action;

    /**
     * Fetch the Condition to evaluate before the Transition is triggered to check if it is allowed.
     *
     * @return Condition
     */
    public function getCondition(): Condition;

    /**
     * Compare this Transition to the provided Transition, and if they are the same.
     *
     * @param ITransition $otherTransition
     * @return bool
     */
    public function equals(ITransition $otherTransition): bool;

    /**
     * Set the StateMachine of the State.
     * This is so that it can have more context.
     *
     * @param IStateMachine $stateMachine
     * @return mixed
     */
    public function setStateMachine(IStateMachine $stateMachine);
}
