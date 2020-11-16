<?php

namespace App\Foundations\Persistence;

use RuntimeException;

class NotFoundException extends RuntimeException
{
    private const MESSAGE = 'No query results for model "%s"';

    public function __construct(string $model)
    {
        parent::__construct(sprintf(self::MESSAGE, $model));
    }
}
