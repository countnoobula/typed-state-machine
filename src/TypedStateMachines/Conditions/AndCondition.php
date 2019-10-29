<?php

namespace TypedStateMachines\Conditions;

class AndCondition extends Condition
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
     * Evaluate all conditions, and return if they all pass.
     *
     * @return bool
     */
    public function evaluate(): bool
    {
        return array_reduce(
            $this->conditions, function ($out, Condition $condition) {
                $evaluation = $condition->resolve();

                if (!$evaluation) {
                    $this->failed[get_class($condition)] = $condition->getFailed();
                }

                return $out && $evaluation;
            }, true
        );
    }
}
