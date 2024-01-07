<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class AssumptionDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['AI', 'FR', 'JU', 'LU', 'NW', 'OW', 'SZ', 'SO', 'TI', 'UR', 'VS', 'ZG'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-08-15', $year));
    }
}
