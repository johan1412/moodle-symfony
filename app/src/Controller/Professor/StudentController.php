<?php

namespace App\Controller\Professor;

use App\Entity\Group;
use App\Entity\User;
use App\Form\EditUserType;
use App\Form\UserType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/student",name="student_")
 */
class StudentController extends AbstractController
{
    public MailerService $sendEmail;
    public UserPasswordEncoderInterface $encoder;

    public function __construct(MailerService $sendEmail, UserPasswordEncoderInterface $encoder)
    {
        $this->sendEmail = $sendEmail;
        $this->encoder = $encoder;
    }
    /**
     * @Route("/{id}", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, User $professor): Response
    {
        $group = $professor->getProfessorGroups();
        $student = $userRepository->StudentWithoutGroup($userRepository->findByRoles("ROLE_STUDENT"));
        return $this->render('professor/student/index.html.twig', [
            'users' => $student,
            'groups' => $group
        ]);
    }


    /**
     * @Route("/{group}/{student}", name="delete", methods={"DELETE"})
     */
    public function delete(Group $group, User $student): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $student->setStudentGroup(null);
        $entityManager->flush();

        $this->addFlash('danger', 'Student removed successfully');
        return $this->redirectToRoute('professor_student_group_index', ['id' => $group->getId()]);
    }
}
