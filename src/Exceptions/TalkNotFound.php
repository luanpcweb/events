<?php

namespace App\Exceptions;

use Exception;

class TalkNotFound extends Exception
{
    private $msg;

    public function __construct(string $msg)
    {
        $this->msg = $msg;
    }
}