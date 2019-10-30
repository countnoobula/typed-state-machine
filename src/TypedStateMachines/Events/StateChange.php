<?php

namespace TypedStateMachines\Events;

use TypedStateMachines\IEvent;
use TypedStateMachines\IStateMachine;
use TypedStateMachines\Transition;

class StateChange implements IEvent
{

    public $stateMachine;
    public $transition;

    public function __construct(IStateMachine $stateMachine, Transition $transition)
    {
        $this->stateMachine = $stateMachine;
        $this->transition   = $transition;
    }

    public function getName(): string
    {
        return 'state_change';
    }
}
