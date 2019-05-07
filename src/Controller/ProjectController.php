<?php

namespace App\Controller;

use App\Network\ServiceCurl;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/project")
 */
class ProjectController extends AbstractController
{

  protected $curl;

  public function __construct(ServiceCurl $curl)
  {
    // parent::__construct();
    $this->curl = $curl;
  }
  /**
   * @Route("/public/index", name="project_public_index", methods={"GET"})
   *
   */
  public function publicIndex(Request $request, ProjectRepository $projectRepository)
  {
    return $this->render('project/public.index.html.twig', [
        'projects' => $projectRepository->findAll(),
    ]);
  }

  /**
  * @Route("/jsongps", name="project_jsongps", methods={"GET"})
  *
  */

  public function jsonGps(Request $request)
  {
      // em entity manager
      $project = [];
      $em = $this->getDoctrine()->getManager();
      //On construit l'url   ?option=pastProject ...
      switch ($request->query->get('option')){
          case 'pastProject':
          $project = $em->getRepository(Project::class)->pastProject(); // var_dump($events); die;
          break;
          case 'futurProject':
          $project = $em->getRepository(Project::class)->futurProject();
          case 'allProject':
          default:
          $project = $em->getRepository(Project::class)->findAll();
      }

      //$events = $em->getRepository('AppBundle:Events')->findAll();
      //$events = $em->getRepository('AppBundle:Events')->find();
      // $curl = $this -> container -> get('App\Network\ServiceCurl');
      // $events = $this -> getEvents();
      $gpsProject = [];



          foreach($project as $p) {

              $adresse = str_replace(' ', '+', $p ->getPlace()); // lorsque la propriété est en privé il faut faire le get

              $suggestions = json_decode($this->curl->curl_get($adresse),true);
              //les données correspondant aux resultats de l'objet json obtenuavec curl.
              //le [0] correspond à la premiere reponse trouvée dans la requete CURLOPT_URL car elle renvoi plusieurs reponses
              $gps  = $suggestions['features'][0]['geometry']['coordinates'];
              $p ->latitude = $gps[1];
              $p ->longitude = $gps[0];
              $gpsProject[] = $p;

          }
           // var_dump(json_encode($gpsProject)); die;
           $response = new JsonResponse();
           $response->setData($gpsProject);
           return $response;
      // return $this->json(json_encode($gpsProject));
                  // ->render('home/mappingLeaflet.html.twig', [
                  //   'project' => $projectRepository->findAll(),
                  // ]

  }

  public function getProjects()
{
    $em = $this->getDoctrine()->getManager();
    $projects = $em->getRepository(Project::class)->findAll();

    return $projects;
}

  /**
   * @Route("/icone", name="icone")
   */
  public function icone(Request $request)
  {
      //$curl = $this -> get('AppBundle\Network\ServiceCurl');
     // $events = $this -> getEvents();
     //$gpsEvents = [];

      //foreach($events as $e) {
          //$adresse = str_replace(' ', '+', $e ->getAdresse()); // lorsque la propriété est en privé il faut faite le get
          // $suggestions = json_decode($curl->curl_get($adresse),true);
          // $gps  = $suggestions['features'][0]['geometry']['coordinates'];
          // $e ->latitude = $gps[1];
          // $e ->longitude = $gps[0];
          // $gpsEvents[] = $e;
      // }
      return $this->render('home/mappingLeaflet.html.twig', [
          'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
          'projects' => $this -> getProjects(),

          // 'latitude' => $gps[1],
          // 'longitude' => $gps[0],
          // 'latitude' => $e->$gps[1],
          // 'longitude' => $e->$gps[0]
      ]);
  }


  /**
  *
  *@Route("/bycreator", name="project_bycreator", methods={"GET"})
  */
  public function projectByCreator()
  {
    //$this->denyAccessUnlessGranted('ROLE_EDITOR');

    $creator= $this->getUser();
    $repository = $this->getDoctrine()->getRepository(Project::class);
    $projectcreator = $repository->findBy(['creator' => $creator->getId()]);

    if(!($this->isGranted('ROLE_EDITOR')))
     {
       throw new AccessDeniedHttpException('You are not allowed to go to these page');


     }
     else {
     return $this->render('project/project.by.creator.html.twig', [

         'project' => $repository->findAll(),
         'projectcreator' => $repository->findBy(['creator' => $creator->getId()]),

         //'projectcreator' => $repository->findByCreator($id),
         //findByCreator($id),
       ]);

     }
     }

    /**
     * @Route("/", name="project_index", methods={"GET"})
     *
     */
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('project/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="project_new", methods={"GET","POST"})
     *
     * @IsGranted("ROLE_EDITOR", message="No access!")
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
    *
    *
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


    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     *
     * @IsGranted("ROLE_EDITOR", message="No access!")
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
     *
     * @IsGranted("ROLE_EDITOR", message="No access!")
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

    /**
     * @Route("/showinhome", name="show_in_home")
     *
     */

    public function showInHome(Project $project)
    {
      $repository = $this->getDoctrine()->getRepository(Project::class);
      $project = $repository->findAll();//find($id);

        return $this->render('home/vignettesproject.html.twig', [
            'project' => $project,

        ]);
    }

    /**
    *
    *
    * @Route("/public/{id}", name="project_public_show", methods={"GET"})
    */
    public function publicShow(Project $project): Response
    {
        $deleteForm = $this->createDeleteForm($project); //test


        return $this->render('project/public.show.html.twig', [
            'project' => $project,
            'delete_form' => $deleteForm->createView(), //test
        ]);
    }

    // /**
    //  * @Route("/indexpublic", name="indexpublic", methods={"GET"})
    //  */
    // public function publicIndex(): Response
    // {
    //   var_dump('OK'); die;
    //     return $this->render('project/public.index.html.twig', [
    //         'projects' => $this->getDoctrine()->getRepository('ProjectRepository')->findAll(),
    //     ]);
    // }
    //


    private function createDeleteForm(Project $project) //TEST
    {
      return $this->createFormBuilder()  //test
        ->setAction($this->generateUrl('project_delete', array('id' => $project->getId()))) //TEST
        ->setMethod('DELETE')  //TEST
        ->getForm()  //TEST
      ;}

}
