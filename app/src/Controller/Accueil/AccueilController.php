<?php

namespace App\Controller\Accueil;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index()
    {
        $user = $this->getUser();
        if(is_null($user)) {
            return $this->redirectToRoute('app_login');
        }
        if(in_array("ROLE_ADMIN", $user->getRoles())) {
            return $this->redirectToRoute('admin_index');
        }
        if(in_array("ROLE_PROFESSOR", $user->getRoles())) {
            return $this->redirectToRoute('professor_index');
        }
        if(in_array("ROLE_STUDENT", $user->getRoles())) {
            return $this->redirectToRoute('student_index');
        }
    }
}
