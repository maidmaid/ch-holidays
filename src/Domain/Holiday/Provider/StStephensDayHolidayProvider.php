<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class StStephensDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['AG', 'AI', 'AR', 'BL', 'BS', 'BE', 'FR', 'GL', 'GR', 'JU', 'LU', 'NE', 'NW', 'OW', 'SG', 'SH', 'SZ', 'SO', 'TG', 'TI', 'UR', 'ZG', 'ZH'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-12-26', $year));
    }
}
