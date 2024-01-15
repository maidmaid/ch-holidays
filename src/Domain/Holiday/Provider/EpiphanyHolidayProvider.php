<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class EpiphanyHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['GR', 'LU', 'SZ', 'TI', 'UR'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-01-06', $year));
    }
}
