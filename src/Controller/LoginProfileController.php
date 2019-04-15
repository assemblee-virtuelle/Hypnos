<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginProfileController extends AbstractController
{
    /**
     * @Route("/loginprofile", name="loginprofile")
     */
    public function index(/*Request $request*/)
    {
      $visitor = $this->getUser();
      $repository = $this->getDoctrine()->getRepository(User::class);
      $users = $repository->find($visitor->getId());
      // $repositorylogincheck =$this->getDoctrine()->getRepository(LoginFormAuthenticator::class);
      // $logincheck = $repositorylogincheck->getUser();

        return $this->render('login_profile/loginProfile.html.twig', [
            'controller_name' => 'LoginProfileController',
            'users' => $users,
            'visitor' => $visitor
            //  'id' => $user->getId(),

        ]);
    }

  
}
