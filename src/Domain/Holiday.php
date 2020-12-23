<?php

namespace App\Domain;

class Holiday
{
    public $cantonId;
    public $periods;

    /**
     * @return \DateTime[]
     */
    public function getDates(): array
    {
        return array_merge(...array_map(function ($range) {
            [$start, $end] = explode('-', $range);

            return iterator_to_array(new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), new \DateTime($end)));
        }, array_values($this->periods)));
    }
}
