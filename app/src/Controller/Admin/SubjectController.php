<?php

namespace App\Controller\Admin;

use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subject",name="subject_")
 */
class SubjectController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(SubjectRepository $subjectRepository): Response
    {
        return $this->render('admin/subject/index.html.twig', [
            'subject' => $subjectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, SubjectRepository $subjectRepository): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if (!$subjectRepository->findOneBy(['name' => $subject->getName()])) {
                $entityManager->persist($subject);
                $entityManager->flush();

                $this->addFlash('success', 'Subject created successfully');

                return $this->redirectToRoute('admin_subject_index');
            }
            $this->addFlash('danger', 'The subject is already exists!');
        }

        return $this->render('admin/subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Subject $subject): Response
    {
        return $this->render('admin/subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Subject $subject): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subject->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subject);
            $entityManager->flush();

            $this->addFlash('danger', 'Subject deleted successfully');
        }

        return $this->redirectToRoute('admin_subject_index');
    }
}
