<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

class CorpusChristiHolidayProvider extends AbstractSimpleHolidayProvider
{
    use EasterTrait;

    protected function getCantons(int $year): iterable
    {
        return ['AG', 'AI', 'FR', 'JU', 'LU', 'NW', 'OW', 'SZ', 'SO', 'TI', 'UR', 'VS', 'ZG'];
    }

    protected function getDates(int $year): iterable
    {
        yield $this->getEasterDate($year)->modify('+50 days');
    }
}
