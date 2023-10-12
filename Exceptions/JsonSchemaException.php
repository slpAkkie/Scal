<?php

namespace Uwi\Loader\Exceptions;

class JsonSchemaException extends \Exception
{
    protected $message = 'Json schema doesn\'t look like a valid one';
    protected $code = E_USER_ERROR;

    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($this->message . '[' . $message . ']', $this->code, $previous);
    }
}
