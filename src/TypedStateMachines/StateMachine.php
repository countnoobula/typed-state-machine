<?php

namespace TypedStateMachines;

use TypedStateMachines\Contracts\HasOnEntry;
use TypedStateMachines\Contracts\HasOnExit;
use LogicException;

abstract class StateMachine implements IStateMachine
{

    /**
     * Creates a new instance of the state machine.
     */
    public static function create(): IStateMachine
    {
        return new static();
    }

    /**
     * @return State
     */
    abstract public function getCurrentState(): State;

    /**
     * @return State[]
     */
    public function getStates(): array
    {
        $edges = $this->getEdges();

        return array_map(
            function (Edge $edge) {
                return $edge->getSourceState();
            }, $edges
        );
    }

    /*
     * This method does some awesome magic- we can make a reliable guess as to what state class
     * you are looking for from a string representation of state. This guess relies on the following:
     * 1. String representation of state must be in snake_case
     * 2. State class must be in PascalCase
     * 3. State class must exist in the namespace {Path/To/StateMachine}/States/{StateName}
     *
     * For example:
     * granted has a state class of Granted and saved in the datastore as granted
     * offers_received has a state class of App\StateMachines\JobStateMachine\States\OffersReceived
     * and saved in the datastore as offers_received
     *
     * @param $string State representation which follows follows the prescibed state format above
     * @return \TypedStateMachines\State
     */
    protected function guessStateClass(string $state): State
    {
        $class_name      = get_called_class();
        $namespace_class = substr($class_name, 0, strrpos($class_name, '\\'))
            . '\\States\\'
            . str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $state)));

        return class_exists($namespace_class) ? new $namespace_class() : new InvalidState();
    }

    /**
     * @return Edge[]
     */
    abstract public function getEdges(): array;

    /**
     * @param Transition $transition
     * @return TransitionResult
     * @throws Exceptions\TransitionFailedConditionsException
     * @throws Exceptions\TransitionNoEdgeException
     */
    public function triggerTransition(Transition $transition): TransitionResult
    {
        $currentState = $this->getCurrentState();

        $currentState->setStateMachine($this);
        $transition->setStateMachine($this);

        $edges = array_filter(
            $this->getEdgesFrom($currentState), function (Edge $edge) use ($transition) {
                return $edge->getTransition()->equals($transition);
            }
        );

        if (count($edges) > 1) {
            throw new LogicException(
                sprintf(
                    "Invalid state machine definition for `%s`. Multiple edges found for source state %s and transition %s.",
                    self::class,
                    $currentState->getName(),
                    $transition->getName()
                )
            );
        }

        if (count($edges) == 0) {
            throw new Exceptions\TransitionNoEdgeException($this->getCurrentState(), $transition);
        }

        $condition = $transition->getCondition();
        if (!$condition->resolve()) {
            $failedConditions = json_encode($condition->getFailed());
            throw new Exceptions\TransitionFailedConditionsException($failedConditions, $transition);
        }

        $oldState = $currentState;

        $transitionResult = new TransitionResult(
            $transition->getAction()->evaluate(),
            null
        );

        if ($oldState instanceof HasOnExit) {
            $oldState->onExit();
        }

        $newState = $this->getCurrentState();

        $edge = reset($edges);
        if ($edge) {
            $newState = $edge->getTargetState();
        }

        $newState->setStateMachine($this);
        if ($newState instanceof HasOnEntry) {
            $newState->onEntry();
        }

        return $transitionResult;
    }

    /**
     * @return Transition[]
     */
    public function getAvailableTransitions(): array
    {
        $currentState = $this->getCurrentState();
        $edges        = $this->getEdgesFrom($currentState);

        return array_map(
            function (Edge $edge) {
                return $edge->getTransition();
            }, $edges
        );
    }

    /**
     * @return Transition[]
     */
    public function getValidAvailableTransitions(): array
    {
        $sm = $this;
        return array_filter(
            $this->getAvailableTransitions(), function (ITransition $transition) use ($sm) {
                $transition->setStateMachine($sm);
                return $transition->getCondition()->resolve();
            }
        );
    }

    /**
     * @param State $sourceState
     * @return State[]
     */
    public function getEdgesFrom(State $sourceState): array
    {
        return array_values(
            array_filter(
                $this->getEdges(), function (Edge $edge) use ($sourceState) {
                    return $edge->getSourceState()->equals($sourceState);
                }
            )
        );
    }

    /**
     * Check if the provided transition is currently available and valid.
     *
     * @param Transition $transition
     * @return bool
     */
    public function isTransitionAvailable(Transition $transition): bool
    {
        return in_array(
            $transition->getName(), array_map(
                function (ITransition $t) {
                    return $t->getName();
                }, $this->getValidAvailableTransitions()
            )
        );
    }
}
