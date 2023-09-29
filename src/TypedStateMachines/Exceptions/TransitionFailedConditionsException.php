<?php

namespace TypedStateMachines\Exceptions;

use TypedStateMachines\Transition;
use TypedStateMachines\TransitionResult;

class TransitionFailedConditionsException extends TransitionException
{
    /**
     * Constructor for the Exception thrown when a Transition fails its conditions.
     *
     * @param string $failedConditions
     * @param Transition $transition
     */
    public function __construct(string $failedConditions, Transition $transition)
    {
        parent::__construct(
            sprintf(
                'Machine could not step. ErrCode: %s. Failed conditions: %s. Transition: %s',
                TransitionResult::ERR_CONDITION_FAILED,
                $failedConditions,
                $transition->getName()
            )
        );
    }
}
