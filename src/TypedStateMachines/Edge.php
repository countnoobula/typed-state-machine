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

    /**
     * Construct the Edge for the StateMachineGraph.
     *
     * @param State $sourceState
     * @param Transition $transition
     * @param State $targetState
     */
    public function __construct(State $sourceState, Transition $transition, State $targetState)
    {
        $this->sourceState = $sourceState;
        $this->transition  = $transition;
        $this->targetState = $targetState;
    }

    /**
     * Fetch the State that this Transition starts from.
     *
     * @return State
     */
    public function getSourceState(): State
    {
        return $this->sourceState;
    }

    /**
     * Fetch the Transition linking the source to the target.
     *
     * @return Transition
     */
    public function getTransition(): Transition
    {
        return $this->transition;
    }

    /**
     * Fetch the State that this Transition points to.
     *
     * @return State
     */
    public function getTargetState(): State
    {
        return $this->targetState;
    }
}
