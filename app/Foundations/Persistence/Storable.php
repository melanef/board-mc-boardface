<?php

namespace App\Foundations\Persistence;

interface Storable
{
    /**
     * @return mixed[]
     */
    public function getStorableFields(): array;
}
