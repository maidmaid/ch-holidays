<?php

namespace App\Domain;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
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
        return $this->serializer->deserialize(
            file_get_contents($this->projectDir.'/data/2021.csv'),
            Holiday::class.'[]',
            'csv',
            [CsvEncoder::DELIMITER_KEY => ';']
        );
    }

    /**
     * @return \DateTime[]
     */
    public function getDates(): array
    {
        return $this->getDatesByHolidays($this->getHolidays());
    }

    /**
     * @return \DateTime[]
     */
    public function getDatesByCantons(array $cantons = []): array
    {
        return $this->getDatesByHolidays(array_filter($this->getHolidays(), function (Holiday $holiday) use ($cantons) {
            return in_array($holiday->canton, $cantons, true);
        }));
    }

    public function getCantons(): array
    {
        return array_map(function (Holiday $holiday) {
            return $holiday->canton;
        }, $this->getHolidays());
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
            return $holiday->canton;
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
