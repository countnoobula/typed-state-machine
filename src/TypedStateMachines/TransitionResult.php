<?php

namespace TypedStateMachines;

class TransitionResult
{
    public const ERR_NO_EDGE_FOUND    = "no_edge";
    public const ERR_CONDITION_FAILED = "conditions_failed";

    protected $error_translations = [
        self::ERR_NO_EDGE_FOUND => 'No edge found.',
        self::ERR_CONDITION_FAILED => 'Condition failed while trying to apply the transition.',
    ];

    /**
     * @var ActionResult|null
     */
    private $actionResult;

    /**
     * @var string|null
     */
    private $errCode;

    /**
     * @var mixed
     */
    private $meta;

    /**
     * Construct the TransitionResult.
     *
     * @param ActionResult|null $actionResult
     * @param string|null $errCode
     * @param mixed $meta
     * @return void
     */
    public function __construct(?ActionResult $actionResult, ?string $errCode, $meta = null)
    {
        $this->actionResult = $actionResult;
        $this->errCode      = $errCode;
        $this->meta         = $meta;
    }

    /**
     * Return if the transition was successful by checking for no error code.
     *
     * @return bool
     */
    public function success(): bool
    {
        return $this->errCode === null;
    }

    /**
     * Return the action result of the transition, if any.
     *
     * @return ActionResult|null
     */
    public function getActionResult(): ?ActionResult
    {
        return $this->actionResult;
    }

    /**
     * Return the error code of the transition, if any.
     *
     * @return string|null
     */
    public function getErrCode(): ?string
    {
        return $this->errCode;
    }

    /**
     * Return a more human readable error message of the transition, if any.
     *
     * @return string|null
     */
    public function getErrMessage(): ?string
    {
        $err_message = $this->getErrCode();

        if (array_key_exists($this->errCode, $this->error_translations)) {
            $err_message = $this->error_translations[$this->errCode];
        }

        return $err_message;
    }

    /**
     * Return the meta information of the transition result, if any.
     *
     * @return mixed
     */
    public function getMeta()
    {
        return $this->meta;
    }
}
