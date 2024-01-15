<?php

namespace App\Command;

use App\Domain\Holiday\HolidayProviderRegistry;
use App\Domain\Holiday\Provider\School\DataProvider as SchoolDataProvider;
use App\Entity\Canton;
use App\Entity\HolidayType;
use App\Repository\CantonRepository;
use App\Repository\HolidayTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Wnx\SwissCantons\Cantons;

#[AsCommand(
    name: 'app:import',
    description: 'Add a short description for your command',
)]
class ImportCommand extends Command
{
    public function __construct(
        private SchoolDataProvider $schoolDataProvider,
        private EntityManagerInterface $em,
        private CantonRepository $cantonRepository,
        private HolidayTypeRepository $holidayTypeRepository,
        private HolidayProviderRegistry $holidayProviderRegistry,
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

        /** @var \Wnx\SwissCantons\Canton $c */
        foreach ($io->progressIterate((new Cantons())->getAll()) as $c) {
            $canton = (new Canton())
                ->setId($c->getAbbreviation())
            ;

            $this->em->persist($canton);
        }

        $this->em->flush();

        $io->section('Importing holiday types');

        foreach ($io->progressIterate($this->holidayProviderRegistry->getTypesIds()) as $typeId) {
            $type = (new HolidayType())
                ->setId($typeId)
            ;
            $this->em->persist($type);
        }
        $this->em->flush();

        foreach ($this->schoolDataProvider->getAvailableYears() as $year) {
            $io->section(sprintf('Importing holidays %s', $year));

            foreach ($io->progressIterate($this->holidayProviderRegistry->provide($year)) as $i => $holiday) {
                $this->em->persist($holiday);
            }
            $this->em->flush();
            $this->em->clear();
        }

        return Command::SUCCESS;
    }
}
