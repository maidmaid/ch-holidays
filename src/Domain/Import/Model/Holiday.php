<?php

namespace App\Domain\Import\Model;

class Holiday
{
    public $cantonId;
    public $periods;

    public function getPeriods(): iterable
    {
        foreach ($this->periods as $period => $range) {
            [$start, $end] = explode('-', $range);

            if ("" === $start || "" === $end) {
                continue;
            }

            yield $period => new \DatePeriod(
                new \DateTime($start),
                new \DateInterval('P1D'),
                (new \DateTime($end))->modify('+1 day')
            );
        }
    }
}
