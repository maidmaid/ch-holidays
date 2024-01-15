<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class NafelsRideHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['GL'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('first thursday april %d', $year));
    }
}
