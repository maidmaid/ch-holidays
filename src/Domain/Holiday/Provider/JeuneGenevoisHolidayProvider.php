<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class JeuneGenevoisHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['GE'];
    }

    protected function getDates(int $year): iterable
    {
        yield (new \DateTimeImmutable(sprintf('first sunday September %s', $year)))->modify('next thursday');
    }
}
