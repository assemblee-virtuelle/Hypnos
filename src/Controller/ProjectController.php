<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("/", name="project_index", methods={"GET"})
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('illustration')->getData();
            $fileName = $file->getClientOriginalName();
            //var_dump ($fileName); die;
      try {
        $file->move(
          $this->getParameter('illustration_directory'),
          $fileName

        );
      } catch (FileException $e) {
        // ... handle exception if something happens during file upload
      }

      $project->setIllustration($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('project_index', array('id' => $project->getId()));
        } else {

        return $this->render('project/new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
      }
    }

    /**
     * @Route("/{id}", name="project_show", methods={"GET"})
     */
    public function show(Project $project): Response
    {
        $deleteForm = $this->createDeleteForm($project); //test

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'delete_form' => $deleteForm->createView(), //test
        ]);
    }

//     public function showClient(Project $project)
// {
//     $deleteForm = $this->createDeleteForm($project);
//
//     return $this->render('project/public.show.html.twig', array(
//         'project' => $project,
//         'delete_form' => $deleteForm->createView(),
//     ));
// }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Project $project): Response
    {
        $deleteForm = $this->createDeleteForm($project);  //test
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('project_index', [
                'id' => $project->getId(),
            ]);
        }

        return $this->render('project/edit.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),//test
        ]);
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Project $project): Response
    {
          $form = $this->createDeleteForm($project); //test
          $form->handleRequest($request); //test


        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('project_index');
    }
    private function createDeleteForm(Project $project) //TEST
    {
      return $this->createFormBuilder()  //test
        ->setAction($this->generateUrl('project_delete', array('id' => $project->getId()))) //TEST
        ->setMethod('DELETE')  //TEST
        ->getForm()  //TEST
;
}
}
