<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

trait EasterTrait
{
    protected function getEasterDate(int $year): \DateTimeImmutable
    {
        return (new \DateTimeImmutable(sprintf('%s-03-21 00:00:00', $year)))
            ->modify(sprintf('+%d days', easter_days($year)))
        ;
    }
}
