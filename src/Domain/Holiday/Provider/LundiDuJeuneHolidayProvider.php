<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class LundiDuJeuneHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['VD'];
    }

    protected function getDates(int $year): iterable
    {
        yield (new \DateTimeImmutable(sprintf('third sunday september %s', $year)))->modify('+ 1 day');
    }
}
