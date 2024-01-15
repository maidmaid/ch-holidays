<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class SaintsPeterAndPaulHolidayProvider extends AbstractSimpleHolidayProvider
{
    protected function getCantons(int $year): iterable
    {
        return ['TI'];
    }

    protected function getDates(int $year): iterable
    {
        yield new \DateTimeImmutable(sprintf('%d-06-29', $year));
    }
}
