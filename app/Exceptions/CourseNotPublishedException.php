<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class CourseNotPublishedException extends Exception
{
    public function __construct()
    {
        parent::__construct(__('exceptions.course_not_published'));
    }
}
