<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class StJosephsDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['GR', 'LE', 'NW', 'SZ', 'SO', 'TI', 'UR', 'VS'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-03-19', $year));
    }
}
