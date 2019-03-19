<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VignetteProjectController extends AbstractController
{
    /**
     * @Route("/vignette/project", name="vignette_project")
     */
    public function index(Project $project)
    {
      $repository = $this->getDoctrine()->getRepository(Project::class);
      $project = $repository->findAll();//find($id);

        return $this->render('vignette_project/index.html.twig', [
            'controller_name' => 'VignetteProjectController',
            'project' => $project,

        ]);
    }


    // public function show(Project $project)
    // {
    //   return $this->render('vignette_project/index.html.twig', [
    //     'controller_name' => 'VignetteProjectController',
    //     'project' => $project,
    //     ]);
    // $project = new Project();
    // $illustration = $project->setIllustration();
    // $illustration = $project->setTitle();
    //}
}
