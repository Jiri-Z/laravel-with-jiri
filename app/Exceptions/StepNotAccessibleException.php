<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class StepNotAccessibleException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('exceptions.step_not_accessible'));
    }
}
