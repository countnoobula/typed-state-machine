<?php

namespace Test\Feature\TypedStateMachines;

use TypedStateMachines\Actions\Action;
use TypedStateMachines\ActionResult;
use TypedStateMachines\Edge;
use TypedStateMachines\Actions\EdgesBuilder;
use TypedStateMachines\IStateMachine;
use TypedStateMachines\State;
use TypedStateMachines\StateMachine;
use TypedStateMachines\Transition;
use PHPUnit\Framework\TestCase;

class SwitchSM extends StateMachine
{
    private $on_or_off = false;

    /**
     * @return State
     */
    public function getCurrentState(): State
    {
        return $this->on_or_off
            ? new On()
            : new Off();
    }

    /**
     * @return Edge[]
     */
    public function getEdges(): array
    {
        /**
         * @var EdgesBuilder
         */
        $builder = new EdgesBuilder();
        return $builder
            ->transition(new SwitchOn())
            ->sources(new Off())->target(new On())
            ->transition(new SwitchOff())
            ->sources(new On())->target(new Off())
            ->transition(new SwitchNop())
            ->sources(new On(), new Off())
            ->build();
    }

    public function setSwitchState(bool $on_or_off)
    {
        $this->on_or_off = $on_or_off;
    }
}

class SwitchState extends State
{
}

class On extends SwitchState
{
}

class Off extends SwitchState
{
}

abstract class SwitchTransition extends Transition
{
    /**
     * @var SwitchSM
     */
    protected $sm;

    public function setStateMachine(IStateMachine $stateMachine) {
        if (! $stateMachine instanceof SwitchSM) throw new \LogicException("unexpected state machine type");
        $this->sm = $stateMachine;
    }

}

class CallbackAction implements Action
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function evaluate(): ?ActionResult
    {
        $cb = $this->callback;
        $cb();
        return null;
    }
}

class SwitchOn extends SwitchTransition
{
    public function getName(): string
    {
        return "--> ON";
    }

    public function getAction(): Action
    {
        return new CallbackAction(function () {
            $this->sm->setSwitchState(true);
        });
    }
}

class SwitchOff extends SwitchTransition
{
    public function getName(): string
    {
        return "--> OFF";
    }

    public function getAction(): Action
    {
        return new CallbackAction(function () {
            $this->sm->setSwitchState(false);
        });
    }
}

class SwitchNop extends SwitchTransition
{
    public function getName(): string
    {
        return "--> NOP";
    }
}


class StateMachineTest extends TestCase
{

    public function testTriggerTransition()
    {
        $sm = new SwitchSM();

        $sm->setSwitchState(false);

        $result = $sm->triggerTransition(new SwitchOn());

        $this->assertTrue($result->success());
        $this->assertEquals(new On, $sm->getCurrentState());
    }

    public function testGetEdgesFromState()
    {
        $sm = new SwitchSM();

        $edges = $sm->getEdgesFrom(new On());

        $this->assertEquals(2, count($edges));
        $this->assertTrue($edges[0] instanceof Edge);
        /** @var Edge $outgoing_edge */
        $outgoing_edge = $edges[0];
        $this->assertTrue($outgoing_edge->getTargetState()->equals(new Off()));
        $this->assertTrue($edges[1] instanceof Edge);
        /** @var Edge $outgoing_edge */
        $outgoing_edge = $edges[1];
        $this->assertTrue($outgoing_edge->getTargetState()->equals(new On()));


        $edges = $sm->getEdgesFrom(new Off());

        $this->assertEquals(2, count($edges));
        $this->assertTrue($edges[0] instanceof Edge);
        /** @var Edge $outgoing_edge */
        $outgoing_edge = $edges[0];
        $this->assertTrue($outgoing_edge->getTargetState()->equals(new On()));
        $this->assertTrue($edges[1] instanceof Edge);
        /** @var Edge $outgoing_edge */
        $outgoing_edge = $edges[1];
        $this->assertTrue($outgoing_edge->getTargetState()->equals(new Off()));


    }
}
