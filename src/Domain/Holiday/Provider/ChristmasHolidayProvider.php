<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

use Wnx\SwissCantons\Cantons;

class ChristmasHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return array_keys((new Cantons())->getAllAsArray());
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-12-25', $year));
    }
}
