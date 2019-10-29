<?php

namespace TypedStateMachines;

interface IStateMachine
{

    /**
     * @return State
     */
    public function getCurrentState(): State;

    /**
     * @return State[]
     */
    public function getStates(): array;

    /**
     * @return Edge[]
     */
    public function getEdges(): array;


    /**
     * @param Transition $transition
     * @return TransitionResult
     */
    public function triggerTransition(Transition $transition): TransitionResult;

     /**
     * @return Transition[]
     */
    public function getAvailableTransitions(): array;

    /**
     * Creates a new instance of the state machine.
     */
    public static function create(): IStateMachine;
}
