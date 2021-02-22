<?php

namespace App\Controller\Admin;

use App\Entity\Group;
use App\Entity\User;
use App\Form\EditProfessorType;
use App\Form\ProfessorType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use App\Services\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/professor",name="professor_")
 */
class ProfessorController extends AbstractController
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
        $groups = $groupRepository->findAll();
        $professors = $userRepository->findByRoles("ROLE_PROFESSOR");
        return $this->render('admin/professor/index.html.twig', [
            'users' => $professors,
            'groups' => $groups
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(ProfessorType::class, $user);
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
                $user->setRoles(['ROLE_PROFESSOR']);

                $entityManager->persist($user);
                $entityManager->flush();

                // Send The credentials to student to login 
                $this->sendEmail->sendEmail($password, $user->getEmail());

                $this->addFlash('success', 'User created successfully');

                return $this->redirectToRoute('admin_professor_index');
            }
            $this->addFlash('danger', 'The email is already exists!');
        }

        return $this->render('admin/professor/new.html.twig', [
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
        return $this->render('admin/professor/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(EditProfessorType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Professor updated successfully');

            return $this->redirectToRoute('admin_professor_index');
        }

        return $this->render('admin/professor/edit.html.twig', [
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

            $this->addFlash('danger', 'Professor deleted successfully');
        }

        return $this->redirectToRoute('admin_professor_index');
    }


    /**
     * @Route("/{group}/{professor}", name="remove", methods={"DELETE"})
     */
    public function remove(Request $request, Group $group, User $professor): Response
    {
        if ($this->isCsrfTokenValid('remove' . $professor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $professor->removeProfessorGroup($group);
            $entityManager->flush();

            $this->addFlash('danger', 'Professor removed successfully');
        }
        return $this->redirectToRoute('admin_professor_group_index', ['id' => $group->getId()]);
    }
}
