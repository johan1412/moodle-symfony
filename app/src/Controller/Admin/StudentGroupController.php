<?php

namespace App\Controller\Admin;

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
        return $this->render('admin/group_student/index.html.twig', [
            'users' => $students,
            'group' => $group,
        ]);
    }


    /**
     * @Route("/new/{student}/{group}/", name="new")
     */
    public function new(User $student, Group $group)
    {
        $em = $this->getDoctrine()->getManager();
        $group->addGroupStudent($student);
        $em->flush();
        return $this->redirectToRoute('admin_student_index');
    }
}
