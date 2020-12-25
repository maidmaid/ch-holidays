<?php

namespace App\Controller;

use App\Domain\Canton;
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

        $cantons = [];
        $weightType = Canton::WEIGHT_TYPE_COUNT;
        if ($form->isSubmitted() && $form->isValid()) {
            $cantons = $form->getData()['cantons'];
            $weightType = $form->getData()['canton_weight_type'];
        }

        return $this->json($holidayManager->getWeightedTimestamps($weightType, $cantons));
    }


    /**
     * @Route("/cantons/{date}", name="cantons")
     */
    public function cantons(\DateTime $date, HolidayManager $holidayManager): Response
    {
        $cantons = array_map(function (Canton $canton) {
            return $canton->id;
        }, $holidayManager->getCantonsByDate($date));

        return $this->json($cantons);
    }
}
