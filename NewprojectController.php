<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NewprojectController extends AbstractController
{
    /**
     * @Route("/newproject", name="newproject")
     */
    public function index()
    {
        return $this->render('newproject/index.html.twig', [
            'controller_name' => 'NewprojectController',
        ]);
    }
}
