<?php

namespace TypedStateMachines\Exceptions;

use TypedStateMachines\State;
use TypedStateMachines\Transition;
use TypedStateMachines\TransitionResult;

class TransitionNoEdgeException extends TransitionException
{

    /**
     * Constructor for the Exception thrown when a Transition does not have the Edge from the current State.
     *
     * @param State $state
     * @param Transition $transition
     */
    public function __construct(State $state, Transition $transition)
    {
        parent::__construct(
            sprintf(
                'Machine could not step. ErrCode: %s. State: %s. Transition: %s',
                TransitionResult::ERR_NO_EDGE_FOUND,
                $state->getName(),
                $transition->getName()
            )
        );
    }
}
