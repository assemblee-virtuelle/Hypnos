<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class VignetteProjectController extends AbstractController
{
    /**
     * @Route("/vignette/project", name="vignette_project")
     *
     * @IsGranted("ROLE_ADMIN, ROLE_USER", message="No access!")
     *
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


}
