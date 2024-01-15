<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class LabourDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['AR', 'BL', 'BS', 'FR', 'JU', 'NE', 'SH', 'SO', 'TG', 'TI', 'ZH'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-05-01', $year));
    }
}
