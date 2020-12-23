<?php

namespace App\Domain;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;

class HolidayManager
{
    private $serializer;
    private $projectDir;

    public function __construct(SerializerInterface $serializer, $projectDir)
    {
        $this->serializer = $serializer;
        $this->projectDir = $projectDir;
    }

    /**
     * @return Holiday[]
     */
    public function getHolidays(): array
    {
        $finder = (new Finder())
            ->in($this->projectDir.'/data/holidays')
        ;

        $holidays = [];
        foreach ($finder as $file) {
            $holidays[] = $this->serializer->deserialize($file->getContents(), Holiday::class.'[]', 'csv');
        }

        return array_merge(...$holidays);
    }

    /**
     * @return Canton[]
     */
    public function getCantons(): array
    {
        return $this->serializer->deserialize(file_get_contents($this->projectDir.'/data/cantons.csv'), Canton::class.'[]', 'csv');

    }

    public function getCanton(string $id): Canton
    {
        foreach ($this->getCantons() as $canton) {
            if ($canton->id === $id) {
                return $canton;
            }
        }
    }

    /**
     * @return \DateTime[]
     */
    public function getDates(): array
    {
        return $this->getDatesByHolidays($this->getHolidays());
    }

    /**
     * @param Canton[] $cantons
     *
     * @return \DateTime[]
     */
    public function getDatesByCantons(array $cantons = []): array
    {
        return $this->getDatesByHolidays(array_filter($this->getHolidays(), function (Holiday $holiday) use ($cantons) {
            return in_array($this->getCanton($holiday->cantonId), $cantons, false);
        }));
    }

    /**
     * @return string[]
     */
    public function getCantonsByDate(\DateTime $date): array
    {
        $filtered = array_filter($this->getHolidays(), function (Holiday $holiday) use ($date) {
            return in_array($date, $holiday->getDates(), false);
        });

        return array_values(array_map(function (Holiday $holiday) {
            return $holiday->cantonId;
        }, $filtered));
    }

    /**
     * @param Holiday[] $holidays
     */
    private function getDatesByHolidays(array $holidays): array
    {
        return array_merge(...array_map(function (Holiday $holiday) {
            return $holiday->getDates();
        }, $holidays));
    }
}
