<?php

namespace Uwi\Exceptions;

class LoaderCannotBeRegisteredException extends \Exception
{
    protected $message = 'Loader cannot be registered';
    protected $code = E_USER_ERROR;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct($this->message, $this->code, $previous);
    }
}
