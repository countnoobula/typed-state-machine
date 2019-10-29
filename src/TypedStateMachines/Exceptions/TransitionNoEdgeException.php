<?php

namespace TypedStateMachines\Exceptions;

use TypedStateMachines\State;
use TypedStateMachines\Transition;
use TypedStateMachines\TransitionResult;

class TransitionNoEdgeException extends TransitionException
{
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
