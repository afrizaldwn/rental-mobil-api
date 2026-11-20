<?php

namespace App\Traits;

use DateTimeInterface;

trait DateFormat
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }
}