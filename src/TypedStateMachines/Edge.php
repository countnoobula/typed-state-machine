<?php

namespace TypedStateMachines;

class Edge
{
    /**
     * @var State
     */
    private $sourceState;

    /**
     * @var Transition
     */
    private $transition;

    /**
     * @var State
     */
    private $targetState;

    public function __construct(State $sourceState, Transition $transition, State $targetState)
    {
        $this->sourceState = $sourceState;
        $this->transition  = $transition;
        $this->targetState = $targetState;
    }

    /**
     * @return State
     */
    public function getSourceState(): State
    {
        return $this->sourceState;
    }

    /**
     * @return Transition
     */
    public function getTransition(): Transition
    {
        return $this->transition;
    }

    /**
     * @return State
     */
    public function getTargetState(): State
    {
        return $this->targetState;
    }
}
