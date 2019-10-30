<?php

namespace TypedStateMachines;

interface IStateMachine
{

    /**
     * Fetch the current State of the StateMachine.
     *
     * @return State
     */
    public function getCurrentState(): State;

    /**
     * Fetch the States of the StateMachine.
     *
     * @return State[]
     */
    public function getStates(): array;

    /**
     * Fetch the Edges of the StateMachine.
     *
     * @return Edge[]
     */
    public function getEdges(): array;

    /**
     * Trigger the provided Transition on the StateMachine.
     *
     * @param Transition $transition
     * @return TransitionResult
     */
    public function triggerTransition(Transition $transition): TransitionResult;

    /**
     * Fetch the available Transitions from the current State.
     *
     * @return Transition[]
     */
    public function getAvailableTransitions(): array;

    /**
     * Creates a new instance of the StateMachine.
     *
     * @return IStateMachine
     */
    public static function create(): IStateMachine;
}
