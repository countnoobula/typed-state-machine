<?php

namespace TypedStateMachines\Actions;

interface IActionResult
{

    /**
     * Get the value of the ActionResult.
     *
     * @return mixed
     */
    public function getValue(); // left untyped on purpose
}
