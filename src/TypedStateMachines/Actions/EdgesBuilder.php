<?php

namespace TypedStateMachines\Actions;

use LogicException;
use TypedStateMachines\Edge;
use TypedStateMachines\State;
use TypedStateMachines\Transition;

class StateMapping
{
    /**
     * @var State
     */
    public $source;
    /**
     * @var State
     */
    public $target;

    public function __construct( $source, State $target)
    {
        $this->source = $source;
        $this->target = $target;
    }
}

class EdgesBuilder
{

    /**
     * @var Edge[]
     */
    private $edges;

    /**
     * @var Transition
     */
    private $current_transition;

    /**
     * @var StateMapping[]
     */
    private $current_mappings;

    public function __construct()
    {
        $this->edges            = [];
        $this->current_mappings = [];
    }

    public function sources(State ...$states): EdgesBuilder
    {
        if ($this->current_mappings != null) {
            throw new LogicException("sources already defined");
        }

        $this->current_mappings = array_map(
            function($state) { return new StateMapping($state, $state);
            },
            $states
        );
        return $this;
    }

    public function target(State $state): EdgesBuilder
    {
        if ($this->current_transition == null) {
            throw new LogicException("transition undefined");
        }

        if ($this->current_mappings == null) {
            throw new LogicException("source undefined");
        }

        $this->current_mappings = array_map(
            function($mapping) use ($state) {
                return new StateMapping($mapping->source, $state);
            },
            $this->current_mappings
        );
        return $this;
    }

    public function transition(Transition $transition): EdgesBuilder
    {
        $this->buildEdges();
        $this->current_transition = $transition;
        return $this;
    }

    private function buildEdges()
    {
        if ($this->current_transition == null) {
            return;
        }

        if (! $this->current_mappings) {
            throw new LogicException("transition ill-defined, missing state mapping");
        }

        $this->edges              = array_merge(
            $this->edges, array_map(
                function($current_mapping) {
                    return new Edge($current_mapping->source, $this->current_transition, $current_mapping->target);
                }, $this->current_mappings
            )
        );
        $this->current_mappings   = null;
        $this->current_transition = null;
    }

    public function build()
    {
        $this->buildEdges();
        return $this->edges;
    }
}
