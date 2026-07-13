<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class OrphanedStepException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('exceptions.orphaned_step'));
    }
}
