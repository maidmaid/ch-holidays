<?php

declare(strict_types=1);

namespace App\Form\Model;

class HolidayFilters
{
    public function __construct(
        public ?int $year = null,
        public array $cantons = [],
        public array $types = [],
    ) {
        $this->year ??= (int) date('Y');
    }
}
