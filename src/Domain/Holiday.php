<?php

namespace App\Domain;

class Holiday
{
    public $canton;
    public $language;
    public $sport;
    public $spring;
    public $summer;
    public $autumn;
    public $christmas;

    /**
     * @return \DateTime[]
     */
    public function getDates(): array
    {
        return array_merge(...array_map(function ($range) {
            [$start, $end] = explode('-', $range);

            return iterator_to_array(new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), new \DateTime($end)));
        }, [$this->sport, $this->spring, $this->summer, $this->autumn, $this->christmas]));
    }
}
