<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider;

use App\Domain\Holiday\HolidayProviderInterface;
use App\Entity\Holiday;
use App\Entity\HolidayType;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\String\s;

abstract class AbstractHolidayProvider implements HolidayProviderInterface
{
    public function __construct(protected EntityManagerInterface $em)
    {
    }

    public function getTypeId(): string
    {
        return s(get_called_class())->afterLast('\\')->beforeLast('HolidayProvider')->snake()->toString();
    }

    protected function createTypedHoliday(): Holiday
    {
        return (new Holiday())
            ->setType($this->em->getReference(HolidayType::class, $this->getTypeId()))
        ;
    }
}
