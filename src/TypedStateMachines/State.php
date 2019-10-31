<?php

namespace TypedStateMachines;

abstract class State implements IState
{

    /**
     * Fetch the name of the State.
     *
     * @return string
     */
    public function getName(): string
    {
        return static::class;
    }

    /**
     * Compare this State to the provided State, and if they are the same.
     *
     * @param IState $otherState
     * @return bool
     */
    public function equals(IState $otherState): bool
    {
        return get_class($this) === get_class($otherState)
            && $this->getName() === $otherState->getName();
    }

    /**
     * Set the StateMachine of the State.
     * This is so that it can have more context.
     *
     * @param IStateMachine $stateMachine
     * @return mixed
     */
    public function setStateMachine(IStateMachine $stateMachine)
    {
    }
}
