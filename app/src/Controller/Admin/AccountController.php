<?php

namespace App\Controller\Admin;

use App\Form\UserFormType;
use App\Form\ChangePasswordFormType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/account",name="account_")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("", name="show", methods="GET")
     */
    public function show(): Response
    {
        return $this->render('admin/account/show.html.twig');
    }

    /**
     * @Route("/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Account updated successfully!');

            return $this->redirectToRoute('admin_account_show');
        }

        return $this->render('admin/account/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/change-password", name="change_password", methods={"GET", "POST"})
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form['newPassword']->getData())
            );

            $em->flush();

            $this->addFlash('success', 'Password updated successfully!');

            return $this->redirectToRoute('admin_account_show');
        }

        return $this->render('admin/account/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
