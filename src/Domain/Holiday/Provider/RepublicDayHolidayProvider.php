<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class RepublicDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['NE'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-03-01', $year));
    }
}
