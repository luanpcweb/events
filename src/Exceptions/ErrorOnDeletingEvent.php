<?php

namespace App\Exceptions;

use Exception;

class ErrorOnDeletingEvent extends Exception
{
    /**
     * @var string
     */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }
}
