<?php

namespace App\Command;

use App\Domain\Import\DataProvider;
use App\Entity\Canton;
use App\Entity\Holiday;
use App\Entity\HolidayType;
use App\Repository\CantonRepository;
use App\Repository\HolidayTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import',
    description: 'Add a short description for your command',
)]
class ImportCommand extends Command
{
    public function __construct(
        private DataProvider $dataProvider,
        private EntityManagerInterface $em,
        private CantonRepository $cantonRepository,
        private HolidayTypeRepository $holidayTypeRepository,
    ) {
        parent::__construct('app:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->section('Clear data');

        $this->em->getConnection()->executeStatement('delete from holiday');
        $this->em->getConnection()->executeStatement('delete from holiday_type');
        $this->em->getConnection()->executeStatement('delete from canton');
        $this->em->getConnection()->executeStatement('update sqlite_sequence set seq = 0 where name = "holiday"');
        $this->em->getConnection()->executeStatement('update sqlite_sequence set seq = 0 where name = "holiday_type"');

        $io->section('Importing cantons');

        /** @var \App\Domain\Import\Model\Canton $cantonData */
        foreach ($io->progressIterate($this->dataProvider->getCantons()) as $cantonData) {
            $canton = (new Canton())
                ->setId($cantonData->id)
                ->setAbbreviation($cantonData->canton)
                ->setText($cantonData->text)
                ->setLanguage($cantonData->language)
            ;

            $this->em->persist($canton);
        }

        $this->em->flush();

        $io->section('Importing holidays');

        /** @var \App\Domain\Import\Model\Holiday $holidayData */
        foreach ($io->progressIterate($this->dataProvider->getHolidays()) as $i => $holidayData) {
            foreach ($holidayData->getPeriods() as $name => $period) {
                if (null === $holidayType = $this->holidayTypeRepository->findOneBy(['name' => $name])) {
                    $holidayType = (new HolidayType())
                        ->setName($name)
                    ;

                    $this->em->persist($holidayType);
                }

                foreach ($period as $date) {
                    $holiday = (new Holiday())
                        ->setCanton($this->cantonRepository->find($holidayData->cantonId))
                        ->setDate(\DateTimeImmutable::createFromMutable($date))
                        ->setType($holidayType)
                    ;

                    $this->em->persist($holiday);
                }
            }

            $this->em->flush();
            $this->em->clear();
        }

        $io->section('Delete "FL" (Liechtenstein) data');

        $this->em->getConnection()->executeStatement('delete from holiday where canton_id = "FL"');
        $this->em->getConnection()->executeStatement('delete from canton where id = "FL"');

        return Command::SUCCESS;
    }
}
