<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

use Wnx\SwissCantons\Cantons;

class SwissNationalDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return array_keys((new Cantons())->getAllAsArray());
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-08-01', $year));
    }
}
