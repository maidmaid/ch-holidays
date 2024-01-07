<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class SaintNicholasOfFlueDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['OW'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-09-25', $year));
    }
}