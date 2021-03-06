<?php

namespace App\Controller;

use App\Domain\HolidayManager;
use App\Form\HolidayFormType;
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
    public function data(Request $request, HolidayManager $holidayManager): Response
    {
        $form = $this->createForm(HolidayFormType::class);
        $form->handleRequest($request);

        $dates = $form->isSubmitted() && $form->isValid()
            ? $holidayManager->getDatesByCantons($form->getData()['cantons'])
            : $holidayManager->getDates()
        ;

        $heatmap = [];
        foreach ($dates as $date) {
            $heatmap[$date->getTimestamp()] = ($heatmap[$date->getTimestamp()] ?? 0) + 1;
        }

        // fills no-data with 0
        foreach (new \DatePeriod(min($dates)->modify('1 jan'), new \DateInterval('P1D'), max($dates)->modify('1 jan next year')) as $date) {
            $heatmap[$date->getTimestamp()] = $heatmap[$date->getTimestamp()] ?? 0;
        }

        return $this->json($heatmap);
    }


    /**
     * @Route("/cantons/{date}", name="cantons")
     */
    public function cantons(\DateTime $date, HolidayManager $holidayManager): Response
    {
        return $this->json($holidayManager->getCantonsByDate($date));
    }
}
