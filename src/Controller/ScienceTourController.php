<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ScienceTourController extends AbstractController
{
    /**
     * @Route("/science/tour", name="science_tour")
     */
    public function index()
    {
        return $this->render('science_tour/index.html.twig', [
            'controller_name' => 'ScienceTourController',
        ]);
    }
}
