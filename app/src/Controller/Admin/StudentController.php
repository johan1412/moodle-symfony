<?php

namespace App\Controller\Admin;

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
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, GroupRepository $groupRepository): Response
    {
        $group = $groupRepository->findAll();
        $student = $userRepository->findByRoles("ROLE_STUDENT");
        return $this->render('admin/student/index.html.twig', [
            'users' => $student,
            'groups' => $group
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if (!$userRepository->findOneBy(['email' => $user->getEmail()])) {

                // Create password to student
                $user->setPassword($this->generateRandomString(9, '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'));
                $password = $user->getPassword();
                // Encode password
                $encoded = $this->encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encoded);

                //Add ROLE_STUDENT
                $user->setRoles(['ROLE_STUDENT']);

                $entityManager->persist($user);
                $entityManager->flush();

                // Send The credentials to student to login 
                $this->sendEmail->sendEmail($password, $user->getEmail());

                $this->addFlash('success', 'Student created successfully');

                return $this->redirectToRoute('admin_student_index');
            }
            $this->addFlash('danger', 'The email is already exists!');
        }

        return $this->render('admin/student/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    public function generateRandomString($length = 6, $characters = '0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/student/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Student updated successfully');

            return $this->redirectToRoute('admin_student_index');
        }

        return $this->render('admin/student/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Student deleted successfully');
        }

        return $this->redirectToRoute('admin_student_index');
    }


    /**
     * @Route("/{group}/{student}", name="remove", methods={"DELETE"})
     */
    public function remove(Request $request, Group $group, User $student): Response
    {
        if ($this->isCsrfTokenValid('remove' . $student->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $student->setStudentGroup(null);
            $entityManager->flush();

            $this->addFlash('danger', 'Student removed successfully');
        }
        return $this->redirectToRoute('admin_student_group_index', ['id' => $group->getId()]);
    }
}
