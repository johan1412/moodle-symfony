<?php

namespace App\Controller\Professor;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/student/group", name="student_group_")
 */
class StudentGroupController extends AbstractController
{
    /**
     * @Route("/{id}", name="index", methods={"GET"})
     */
    public function index(Group $group): Response
    {
        $students = $group->getGroupStudents();
        return $this->render('professor/group_student/index.html.twig', [
            'users' => $students,
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
