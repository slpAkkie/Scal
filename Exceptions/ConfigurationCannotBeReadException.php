<?php

namespace Uwi\Exceptions;

class ConfigurationCannotBeReadException extends \Exception
{
    protected $message = 'Configuration cannot be read';
    protected $code = E_USER_ERROR;

    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($this->message . ' [' . $message . ']', $this->code, $previous);
    }
}
