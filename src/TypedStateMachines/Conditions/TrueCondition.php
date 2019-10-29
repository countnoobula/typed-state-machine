<?php

namespace TypedStateMachines\Conditions;

class TrueCondition extends Condition
{

    /**
     * Always return true. This is a placeholder condition for the base transition class.
     *
     * @return bool
     */
    public function evaluate(): bool
    {
        return true;
    }
}
