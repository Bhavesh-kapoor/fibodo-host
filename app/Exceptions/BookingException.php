<?php

namespace App\Exceptions;

use Exception;

class BookingException extends Exception
{
    protected $data;

    public function __construct(string $message, int $code = 0, array $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
