<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class ImmaculateConceptionHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['AG', 'AI', 'FR', 'GR', 'LU', 'NW', 'OW', 'SZ', 'SO', 'TI', 'UR', 'VS', 'ZG'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-12-08', $year));
    }
}
