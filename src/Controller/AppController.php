<?php

namespace App\Controller;

use App\Entity\Canton;
use App\Form\HolidayFiltersType;
use App\Form\HolidayFormType;
use App\Form\Model\HolidayFilters;
use App\Repository\CantonRepository;
use App\Repository\HolidayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Wnx\SwissCantons\Canton as WnxCanton;

class AppController extends AbstractController
{
    public function __construct()
    {
        ini_set('date.timezone', 'UTC');
    }

    /**
     * @Route("/", name="app")
     */
    public function index(): Response
    {
        $form = $this->createForm(HolidayFiltersType::class);

        return $this->render('app/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/data", name="data")
     */
    public function data(Request $request, HolidayRepository $holidayRepository): Response
    {
        $filters = new HolidayFilters();
        $form = $this->createForm(HolidayFiltersType::class, $filters);

        $qb = $holidayRepository->createQueryBuilder('h')
            ->addSelect('COUNT(DISTINCT h.canton) as count')
            ->where('h.date BETWEEN :start AND :end')
            ->groupBy('h.date')
            ->orderBy('h.date', 'ASC')
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ([] !== $cantons = $filters->cantons) {
                $qb
                    ->andWhere('h.canton IN (:cantons)')
                    ->setParameter('cantons', array_map(static fn (WnxCanton $canton): string => $canton->getAbbreviation(), $cantons))
                ;
            }

            if ([] !== $types = $filters->types) {
                $qb
                    ->andWhere('h.type IN (:types)')
                    ->setParameter('types', $types)
                ;
            }
        }

        $qb
            ->setParameter('start', sprintf('%d-01-01', $filters->year))
            ->setParameter('end', sprintf('%d-12-31', $filters->year))
        ;

        $holidaysCounts = $qb->getQuery()->getResult();

        $heatmap = [];
        foreach ($holidaysCounts as $holidayCount) {
            $heatmap[] = [
                'date' => $holidayCount[0]->getDate()->getTimestamp() * 1000,
                'count' => $holidayCount['count'],
            ];
        }

        return $this->json($heatmap);
    }

    /**
     * @Route("/cantons/{date}", name="cantons")
     */
    public function cantons(\DateTimeImmutable $date, CantonRepository $cantonRepository): Response
    {
        $cantons = $cantonRepository->createQueryBuilder('c')
            ->join('c.holidays', 'h')
            ->where('h.date = :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->getResult()
        ;

        return $this->json(array_map(static fn (Canton $c): string => $c->getId(), $cantons));
    }
}
