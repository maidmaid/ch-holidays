<?php

declare(strict_types=1);

namespace App\Domain\Holiday\Provider\School;

use App\Domain\Holiday\Provider\AbstractHolidayProvider;
use App\Entity\Canton;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractSchoolHolidayProvider extends AbstractHolidayProvider
{
    public function __construct(EntityManagerInterface $em, private DataProvider $dataProvider)
    {
        parent::__construct($em);
    }

    public function provide(int $year): iterable
    {
        $schools = iterator_to_array($this->dataProvider->getSchools());

        foreach ($this->dataProvider->getHolidays($year) as $holidayData) {
            foreach ($holidayData->getPeriods() as $type => $period) {
                if ($type !== $this->getTypeId()) {
                    continue;
                }

                foreach ($period as $date) {
                    yield $this->createTypedHoliday()
                        ->setCanton($this->em->getReference(Canton::class, $schools[$holidayData->schoolId]->canton))
                        ->setDate(\DateTimeImmutable::createFromMutable($date))
                    ;
                }
            }
        }
    }
}
