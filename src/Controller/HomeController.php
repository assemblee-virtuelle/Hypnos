<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
     public function index()
     {
       $repository = $this->getDoctrine()->getRepository(Project::class);
       $projects = $repository->findAll();//find($id);

        return $this->render('home/index.html.twig', [
                  'controller_name' => 'HomeController',
                  'projects' => $projects,
         ]);


     }


}
