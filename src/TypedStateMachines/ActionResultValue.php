<?php

namespace TypedStateMachines;

class ActionResultValue extends ActionResult
{
    private $value;

    /**
     * Constructor of the ActionResultValue from the Action.
     * @param $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of the ActionResultValue.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
