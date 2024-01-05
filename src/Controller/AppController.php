<?php

namespace App\Controller;

use App\Entity\Canton;
use App\Form\HolidayFormType;
use App\Repository\CantonRepository;
use App\Repository\HolidayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(): Response
    {
        $form = $this->createForm(HolidayFormType::class);

        return $this->render('app/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/data", name="data")
     */
    public function data(Request $request, HolidayRepository $holidayRepository): Response
    {
        $year = $request->get('year', date('Y'));

        $form = $this->createForm(HolidayFormType::class);

        $qb = $holidayRepository->createQueryBuilder('h')
            ->addSelect('COUNT(DISTINCT c.canton) as count')
            ->join('h.canton', 'c')
            ->where('h.date BETWEEN :start AND :end')
            ->setParameter('start', $year.'-01-01')
            ->setParameter('end', $year.'-12-31')
            ->groupBy('h.date')
            ->orderBy('h.date', 'ASC')
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $qb->andWhere('c IN (:cantons)')
                ->setParameter('cantons', $form->getData()['cantons'])
            ;
        }

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
