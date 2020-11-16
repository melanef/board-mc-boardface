<?php

namespace App\Messages\Contexts;

use DateTime;

class FindMessageContext
{
    /** @var string */
    public $subjectQuery;

    /** @var int */
    public $createdBy;

    /** @var DateTime */
    public $expirationDate;
}
