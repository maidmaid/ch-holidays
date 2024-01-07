<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class WhitMondayHolidayProvider extends AbstractSimpleHolidayProvider
{
    use EasterTrait;

    protected function getCantons(int $year): iterable
    {
        return ['AG', 'AI', 'AR', 'BL', 'BS', 'BE', 'FR', 'GE', 'GL', 'GR', 'JU', 'LU', 'NE', 'NW', 'OW', 'SG', 'SH', 'SZ', 'SO', 'TG', 'TI', 'UR', 'VD', 'ZG', 'ZH'];
    }

    protected function getDates(int $year): iterable
    {
        yield $this->getEasterDate($year)->modify('+50 days');
    }
}
