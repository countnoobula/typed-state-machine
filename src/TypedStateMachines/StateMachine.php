<?php

namespace TypedStateMachines;

use TypedStateMachines\Contracts\HasOnEntry;
use TypedStateMachines\Contracts\HasOnExit;
use TypedStateMachines\Exceptions\TransitionNoEdgeException;
use TypedStateMachines\Exceptions\TransitionFailedConditionsException;
use TypedStateMachines\Events\StateChange;
use TypedStateMachines\Events\TransitionSuccess;
use LogicException;

abstract class StateMachine implements IStateMachine
{

    /**
     * Creates a new instance of the StateMachine.
     *
     * @return IStateMachine
     */
    public static function create(): IStateMachine
    {
        return new static();
    }

    /**
     * Fetch the current State of the StateMachine.
     *
     * @return State
     */
    abstract public function getCurrentState(): State;

    /**
     * Fetch the States of the StateMachine.
     *
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

    /**
     * This method does some awesome magic- we can make a reliable guess as to what state class
     * you are looking for from a string representation of state. This guess relies on the following:
     * 1. String representation of state must be in snake_case
     * 2. State class must be in PascalCase
     * 3. State class must exist in the namespace {Path/To/StateMachine}/States/{StateName}
     *
     * For example:
     * on has a state class of On and saved in the datastore as on
     * on has a state class of App\TypedStateMachines\ToasterStateMachine\States\On
     * and saved in the datastore as on.
     *
     * @param string State representation which follows follows the prescibed state format above
     * @return State
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
     * Fetch the Edges of the StateMachine.
     *
     * @return Edge[]
     */
    abstract public function getEdges(): array;

    /**
     * Trigger the provided Transition on the StateMachine.
     * If the Transition is successful, it will fire the TransitionSuccess event.
     * If the Transition causes a State change, it will fire the StateChange event.
     *
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
            throw new TransitionNoEdgeException($this->getCurrentState(), $transition);
        }

        $condition = $transition->getCondition();
        if (!$condition->resolve()) {
            $failedConditions = json_encode($condition->getFailed());
            throw new TransitionFailedConditionsException($failedConditions, $transition);
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

        $transitionSuccessEvent = new TransitionSuccess($this, $transition);
        if ($this->shouldFireEvent($transitionSuccessEvent)) {
            $this->fireEvent($transitionSuccessEvent);
        }

        $stateChangeEvent = new StateChange($this, $transition);
        if ($currentState !== $newState && $this->shouldFireEvent($stateChangeEvent)) {
            $this->fireEvent($stateChangeEvent);
        }

        return $transitionResult;
    }

    /**
     * Fetch the available Transitions from the current State.
     *
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
     * Fetch the available Transitions, with passing conditions, from the current State.
     *
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
     * Fetch the Edges from the provided State.
     *
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

    /**
     * Should the state machine fire events for StateChange and TransitionSuccess.
     *
     * @param IEvent $event
     * @return bool
     */
    public function shouldFireEvent(IEvent $event): bool
    {
        return true;
    }

    /**
     * Fires the provided event into the bus.
     *
     * @param IEvent $event
     * @return void
     */
    public function fireEvent(IEvent $event)
    {
        $class     = get_class($event);
        $listeners = $this->getListeners();

        if (array_key_exists($class, $listeners)) {
            foreach ($listeners[$class] as $listener) {
                /** @var IListener $listenerInstance */
                $listenerInstance = new $listener();
                $listenerInstance->handle($event);
            }
        }
    }

    /**
     * Fetch the listeners for the events.
     *
     * @return array
     */
    public function getListeners(): array
    {
        return [
            StateChange::class => [],
            TransitionSuccess::class => [],
        ];
    }
}
