<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

use App\Entity\Canton;
use App\Entity\Holiday;
use App\Entity\HolidayType;

abstract class AbstractSimpleHolidayProvider extends AbstractHolidayProvider
{
    /**
     * @return iterable<string>
     */
    abstract protected function getCantons(int $year): iterable;

    /**
     * @return iterable<\DateTimeImmutable>
     */
    abstract protected function getDates(int $year): iterable;

    public function provide(int $year): iterable
    {
        foreach ($this->getCantons($year) as $canton) {
            foreach ($this->getDates($year) as $date) {
                yield (new Holiday())
                    ->setType($this->em->getReference(HolidayType::class, $this->getTypeId()))
                    ->setCanton($this->em->getReference(Canton::class, $canton))
                    ->setDate($date)
                ;
            }
        }
    }
}
