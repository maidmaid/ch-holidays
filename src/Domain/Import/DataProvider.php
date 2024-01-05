<?php

namespace App\Domain\Import;

use App\Domain\Import\Model\Canton;
use App\Domain\Import\Model\Holiday;
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
    public function getHolidays(): array
    {
        $finder = (new Finder())
            ->in($this->projectDir.'/data/import/holidays')
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
        return $this->serializer->deserialize(file_get_contents($this->projectDir.'/data/import/cantons.csv'), Canton::class.'[]', 'csv');
    }
}
