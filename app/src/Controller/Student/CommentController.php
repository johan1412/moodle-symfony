<?php

namespace App\Controller\Student;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ExerciseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{

    /**
     * @Route("/new", name="new", methods={"POST"})
     */
    public function new(Request $request, ExerciseRepository $exerciseRepository)
    {
        if(!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('status' => 'Error'),400);
        }
        if(!isset($request->request)) {
            return new JsonResponse(array('status' => 'Error'),400);
        }
        $input = $request->request->get('commentInput');
        $exerciseId = $request->request->get('exerciseId');
        if($input !== "") {
            $comment = new Comment();
            $comment->setText($input);
            $comment->setUsers($this->getUser());
            $comment->setExercises($exerciseRepository->find($exerciseId));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            $lastname = $this->getUser()->getLastName();
            $firstname = $this->getUser()->getFirstName();
            return new JsonResponse(['comment' => $comment->getText(), 'userlastname' => $lastname, 'userfirstname' => $firstname], 201);
        }
    }


    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('student/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comment_index');
    }
}
