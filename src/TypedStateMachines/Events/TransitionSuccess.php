<?php

namespace TypedStateMachines\Events;

use TypedStateMachines\IEvent;
use TypedStateMachines\IStateMachine;
use TypedStateMachines\Transition;

class TransitionSuccess implements IEvent
{

    public $stateMachine;
    public $transition;

    /**
     * TransitionSuccess constructor.
     * @param IStateMachine $stateMachine
     * @param Transition $transition
     */
    public function __construct(IStateMachine $stateMachine, Transition $transition)
    {
        $this->stateMachine = $stateMachine;
        $this->transition   = $transition;
    }

    /**
     * Fetch the name of the Event.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'transition_success';
    }
}
