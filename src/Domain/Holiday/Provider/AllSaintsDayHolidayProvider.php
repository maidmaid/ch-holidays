<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class AllSaintsDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['AG', 'AI', 'FR', 'GL', 'JU', 'LU', 'NW', 'OW', 'SG', 'SZ', 'SO', 'TI', 'UR', 'VS', 'ZG'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-11-01', $year));
    }
}
