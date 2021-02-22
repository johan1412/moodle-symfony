<?php

namespace App\Controller\Professor;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/group",name="group_")
 */
class GroupController extends AbstractController
{
    /**
     * @Route("/{id}", name="index", methods={"GET"})
     */
    public function index(User $professor): Response
    {
        return $this->render('professor/group/index.html.twig', [
            'groups' => $professor->getProfessorGroups(),
        ]);
    }
}
