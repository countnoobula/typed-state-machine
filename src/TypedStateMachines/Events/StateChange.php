<?php

namespace TypedStateMachines\Events;

use TypedStateMachines\IEvent;
use TypedStateMachines\IStateMachine;
use TypedStateMachines\Transition;

class StateChange implements IEvent
{

    /**
     * @var IStateMachine
     */
    public $stateMachine;

    /**
     * @var Transition
     */
    public $transition;

    /**
     * Constructor for the StateChange event.
     *
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
        return 'state_change';
    }
}
