<?php

namespace Snowdog\Academy\Model;

class NumberOfDays
{
    private int $number_of_days;

    public function __construct(int $number)
    {
        $this->number_of_days = $number;
    }

    public function getNumber(): int
    {
        return $this->number_of_days;
    }
}
