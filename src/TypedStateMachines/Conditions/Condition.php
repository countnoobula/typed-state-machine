<?php

namespace TypedStateMachines\Conditions;

abstract class Condition
{
    /**
     * @var array
     */
    protected $failed = [];

    /**
     * Return the failed conditions.
     *
     * @return array
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * Resolve the condition and if it fails, add it's class name to the failed conditions array.
     *
     * @return bool
     */
    public function resolve(): bool
    {
        $evaluation = $this->evaluate();

        return $evaluation;
    }

    /**
     * Evaluate the condition and return if it passes.
     *
     * @return bool
     */
    abstract public function evaluate(): bool;
}
