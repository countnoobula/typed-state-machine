<?php

namespace TypedStateMachines\Conditions;

class OrCondition extends Condition
{
    /**
     * @var Condition[]
     */
    private $conditions;

    public function __construct(Condition ...$conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * Evaluate all conditions, and return true if any of the conditions pass.
     *
     * @return bool
     */
    public function evaluate(): bool
    {
        $passed = false;

        foreach ($this->conditions as $condition) {
            $evaluation = $condition->resolve();
            if ($evaluation) {
                $passed = true;
            } else {
                $this->failed[get_class($condition)] = $condition->getFailed();
            }
        }

        return $passed;
    }
}
