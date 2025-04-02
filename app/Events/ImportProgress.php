<?php

namespace App\Events;

class ImportProgress
{
    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }
} 