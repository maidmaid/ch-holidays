<?php

namespace App\Domain\Import\Model;

class Holiday
{
    public $cantonId;
    public $types;

    public function getPeriods(): iterable
    {
        foreach ($this->types as $type => $period) {
            [$start, $end] = explode('-', $period);

            if ("" === $start || "" === $end) {
                continue;
            }

            yield $type => new \DatePeriod(
                new \DateTime($start),
                new \DateInterval('P1D'),
                (new \DateTime($end))->modify('+1 day')
            );
        }
    }
}
