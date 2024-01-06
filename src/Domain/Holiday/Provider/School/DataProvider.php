<?php

namespace App\Domain\Holiday\Provider\School;

use App\Domain\Holiday\Provider\School\Model\Holiday;
use App\Domain\Holiday\Provider\School\Model\School;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;

class DataProvider
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
    public function getHolidays(int $year): array
    {
        $finder = (new Finder())
            ->in($this->projectDir.'/data/school/holidays')
            ->path(sprintf('%d.csv', $year))
        ;

        $holidays = [];
        foreach ($finder as $file) {
            $holidays[] = $this->serializer->deserialize($file->getContents(), Holiday::class.'[]', 'csv');
        }

        return array_merge(...$holidays);
    }

    /**
     * @return School[]
     */
    public function getSchools(): iterable
    {
        /** @var School[] $schools */
        $schools = $this->serializer->deserialize(file_get_contents($this->projectDir.'/data/school/schools.csv'), School::class.'[]', 'csv');

        foreach ($schools as $school) {
            yield $school->id => $school;
        }
    }
}
