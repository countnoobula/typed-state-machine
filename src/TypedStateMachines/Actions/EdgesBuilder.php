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

    /**
     * Constructor of the StateMapping class.
     *
     * @param State $source
     * @param State $target
     */
    public function __construct(State $source, State $target)
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

    /**
     * Constructor of the EdgesBuilder class.
     */
    public function __construct()
    {
        $this->edges            = [];
        $this->current_mappings = [];
    }

    /**
     * Specify the sources of an Edge.
     *
     * @param State ...$states
     * @return EdgesBuilder
     */
    public function sources(State ...$states): EdgesBuilder
    {
        if ($this->current_mappings != null) {
            throw new LogicException("Sources already defined");
        }

        $this->current_mappings = array_map(
            function($state) { return new StateMapping($state, $state);
            },
            $states
        );
        return $this;
    }

    /**
     * Specify the target of an Edge.
     *
     * @param State $state
     * @return EdgesBuilder
     */
    public function target(State $state): EdgesBuilder
    {
        if ($this->current_transition == null) {
            throw new LogicException("Transition undefined");
        }

        if ($this->current_mappings == null) {
            throw new LogicException("Source undefined");
        }

        $this->current_mappings = array_map(
            function($mapping) use ($state) {
                return new StateMapping($mapping->source, $state);
            },
            $this->current_mappings
        );
        return $this;
    }

    /**
     * Specify the Transition of the Edge.
     *
     * @param Transition $transition
     * @return EdgesBuilder
     */
    public function transition(Transition $transition): EdgesBuilder
    {
        $this->buildEdges();
        $this->current_transition = $transition;
        return $this;
    }

    /**
     * Build the current Transition/source/target into the edges array.
     *
     * @return void
     */
    private function buildEdges()
    {
        if ($this->current_transition === null) {
            return;
        }

        if (! $this->current_mappings) {
            throw new LogicException("Transition ill-defined, missing state mapping");
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

    /**
     * Build all Edges and return the Edges of the builder.
     *
     * @return array|Edge[]
     */
    public function build()
    {
        $this->buildEdges();
        return $this->edges;
    }
}
