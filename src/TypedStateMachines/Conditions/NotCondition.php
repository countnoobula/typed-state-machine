<?php

namespace TypedStateMachines\Conditions;

class NotCondition extends Condition
{
    /**
     * @var Condition[]
     */
    private $condition;

    public function __construct(Condition $condition)
    {
        $this->condition = $condition;
    }

    /**
     * Evaluate the condition and return true if it fails.
     *
     * @return bool
     */
    public function evaluate(): bool
    {
        $evaluation = $this->condition->resolve();

        if ($evaluation) {
            $this->failed[get_class($this->condition)] = $this->condition->getFailed();
        }

        return !$evaluation;
    }
}
