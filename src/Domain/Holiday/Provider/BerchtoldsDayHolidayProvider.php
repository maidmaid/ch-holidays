<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class BerchtoldsDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['AG', 'BE', 'FR', 'GL', 'JU', 'LU', 'NE', 'OW', 'SH', 'SO', 'TG', 'VD', 'ZG', 'ZH'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-01-02', $year));
    }
}
