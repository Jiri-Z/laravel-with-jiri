<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class NotEnrolledException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('exceptions.not_enrolled'));
    }
}
