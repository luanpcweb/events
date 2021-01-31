<?php

namespace App\Exceptions;

use Exception;

class TalkNotFound extends Exception
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
