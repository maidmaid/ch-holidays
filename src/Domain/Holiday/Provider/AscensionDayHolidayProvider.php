<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

use Wnx\SwissCantons\Cantons;

class AscensionDayHolidayProvider extends AbstractSimpleHolidayProvider
{
    use EasterTrait;

    protected function getCantons(int $year): iterable
    {
        return array_keys((new Cantons())->getAllAsArray());
    }

    protected function getDates(int $year): iterable
    {
        yield $this->getEasterDate($year)->modify('+39 days');
    }
}
