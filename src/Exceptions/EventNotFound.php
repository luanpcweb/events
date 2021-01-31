<?php

namespace App\Exceptions;

use Exception;

class EventNotFound extends Exception
{
    /**
     * @var string
     */
    private $msg;

    public function __construct(string $msg)
    {
        $this->msg = $msg;
    }
}
