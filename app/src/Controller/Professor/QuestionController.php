<?php

namespace App\Controller\Professor;

use App\Entity\CorrectAnswer;
use App\Entity\Exercise;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\ExerciseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question",name="question_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        // dd($questionRepository->findAll());
        return $this->render('professor/question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, Exercise $exercise): Response
    {

        if ($request->getMethod() == "POST") {
            $question = new Question();
            $question->setName($request->request->get('name'));
            $question->setDescription($request->request->get('description'));
            $question->setDifficulty($request->request->get('difficulty'));
            $question->setType($request->request->get('type'));

            $question->setCorrectAnswer($request->request->get('answer'));
            $question->addOtherAnswer($request->request->get('answer'));

            $entityManager = $this->getDoctrine()->getManager();

            $question->setExercises($exercise);

            $i = 1;

            while ($request->request->get('answer' . $i)) {
                $question->addOtherAnswer($request->request->get('answer' . $i));
                $i++;
            }


            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question created successfully');

            return $this->redirectToRoute('professor_exercise_show', ['id' => $exercise->getId()]);
        }

        return $this->render('professor/question/new.html.twig', [
            'exercice' => $exercise->getId()
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        return $this->render('professor/question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{exercise}/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question, $exercise): Response
    {
        if ($request->getMethod() == "POST") {
            $question->setName($request->request->get('name'));
            $question->setDescription($request->request->get('description'));
            $question->setDifficulty($request->request->get('difficulty'));
            $question->setType($request->request->get('type'));
            $question->setCorrectAnswer($request->request->get('answer'));
            $question->setOtherAnswers(array());
            $question->addOtherAnswer($request->request->get('answer'));

            $entityManager = $this->getDoctrine()->getManager();
            $exercise = $this->getDoctrine()
                ->getRepository(Exercise::class)
                ->find($exercise);
            $question->setExercises($exercise);

            $i = 1;

            while ($request->request->get('answer' . $i)) {
                $question->addOtherAnswer($request->request->get('answer' . $i));
                $i++;
            }


            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question updated successfully');

            return $this->redirectToRoute('professor_exercise_show', ['id' => $exercise->getId()]);
        }

        return $this->render('professor/question/edit.html.twig', [
            'exercice' => $exercise,
            'question' => $question
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();

            $this->addFlash('danger', 'Question deleted successfully');
        }

        return $this->redirectToRoute('professor_exercise_show', ['id' => $question->getExercises()->getId()]);
    }
}
