<?php

namespace App\Domain\Holiday\Provider\School\Model;

class Holiday
{
    public $schoolId;
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
