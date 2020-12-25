<?php

namespace App\Domain;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class HolidayManager
{
    private $serializer;
    private $projectDir;
    private $cache;

    public function __construct(SerializerInterface $serializer, $projectDir, CacheInterface $cache)
    {
        $this->serializer = $serializer;
        $this->projectDir = $projectDir;
        $this->cache = $cache;
    }

    /**
     * @return Holiday[]
     */
    public function getHolidays(): array
    {
        return $this->cache->get('holidays', function (ItemInterface $item) {
            $finder = (new Finder())
                ->in($this->projectDir.'/data/holidays')
            ;

            $holidays = [];
            foreach ($finder as $file) {
                $holidays[] = $this->serializer->deserialize($file->getContents(), Holiday::class.'[]', 'csv');
            }

            return array_merge(...$holidays);
        });
    }

    /**
     * @return Canton[]
     */
    public function getCantons(): array
    {
        return $this->cache->get('cantons', function (ItemInterface $item) {
            return $this->serializer->deserialize(file_get_contents($this->projectDir.'/data/cantons.csv'), Canton::class.'[]', 'csv');
        });
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
     * @return Canton[]
     */
    public function getCantonsByDate(\DateTime $date): array
    {
        $filtered = array_filter($this->getHolidays(), function (Holiday $holiday) use ($date) {
            return in_array($date, $holiday->getDates(), false);
        });

        return array_map(function (Holiday $holiday) {
            return $this->getCanton($holiday->cantonId);
        }, $filtered);
    }

    /**
     * @param Canton[] $cantons
     */
    public function getWeightedTimestamps(string $weightType, array $cantons = []): array
    {
        $timestamps = [];
        foreach ($this->getHolidays() as $holiday) {
            $canton = $this->getCanton($holiday->cantonId);
            if ($cantons && !in_array($canton, $cantons, false)) {
                continue;
            }
            $weight = $canton->weight[$weightType];
            foreach ($holiday->getDates() as $date) {
                $timestamps[$date->getTimestamp()] = ($timestamps[$date->getTimestamp()] ?? 0) + $weight;
            }
        }

        return $timestamps;
    }

    public function getBoundariesWeights(string $weightType): array
    {
        $timestamps = $this->getWeightedTimestamps($weightType);

        return [min($timestamps), max($timestamps)];
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
