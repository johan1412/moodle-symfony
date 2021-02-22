<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/professor/group", name="professor_group_")
 */
class ProfessorGroupController extends AbstractController
{
    /**
     * @Route("/{id}", name="index", methods={"GET"})
     */
    public function index(Group $group): Response
    {
        $professors = $group->getGroupProfessors();
        return $this->render('admin/group_professor/index.html.twig', [
            'users' => $professors,
            'group' => $group,
        ]);
    }


    /**
     * @Route("/new/{professor}/{group}/", name="new")
     */
    public function new(User $professor, Group $group)
    {
        $em = $this->getDoctrine()->getManager();
        $group->addGroupProfessor($professor);
        $em->flush();
        return $this->redirectToRoute('admin_professor_index');
    }
}
