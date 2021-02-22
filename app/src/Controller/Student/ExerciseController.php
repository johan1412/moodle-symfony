<?php

namespace App\Controller\Student;

use App\Entity\Exercise;
use App\Entity\Question;

use App\Entity\StudentAnswer;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\EditExerciseType;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercise",name="exercise_")
 */
class ExerciseController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(ExerciseRepository $exerciseRepository): Response
    {
        if($this->getUser()->getStudentGroup()){
            return $this->render('student/exercise/index.html.twig', [
                'exercises' => $this->getUser()->getStudentGroup()->getExercises()
            ]);
        }else{
            return $this->render('student/exercise/index.html.twig');
        }
    }

    /**
     * @Route("/list/{subject}", name="list", methods={"GET"})
     */
    public function list(ExerciseRepository $exerciseRepository, Subject $subject): Response
    {
            return $this->render('student/exercise/index.html.twig', [
                'exercises' => $exerciseRepository->getBySubjectAndStudent($this->getUser(), $subject)
            ]);

    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Exercise $exercise): Response
    {
        if($exercise->getSolvedBy()->contains($this->getUser())){
            $repository = $this->getDoctrine()->getRepository(Question::class);
            $answers =  $repository->getFromExercise($exercise);

            return $this->render('student/exercise/show_solved.html.twig', [
                'exercise' => $exercise,
                'answers' => $answers
            ]);
        }else {
            return $this->render('student/exercise/show.html.twig', [
                'exercise' => $exercise,
            ]);
        }
    }

    /**
     * @Route("/submit/{id}", name="submit", methods={"POST"})
     */
    public function submit(Exercise $exercise, Request $request): Response
    {
        $answers = $request->request;
        foreach ($exercise->getQuestions() as $question){
            $studentAnswer = new StudentAnswer();
            $studentAnswer->setQuestion($question);
            $studentAnswer->setStudent($this->getUser());
            $studentAnswer->setResult($request->request->get($question->getId()));
            $entityManager = $this->getDoctrine()->getManager();
            $exercise->addSolvedBy($this->getUser());

            $entityManager->persist($studentAnswer);
            $entityManager->persist($exercise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('student_exercise_show', [
            'id' => $exercise->getId(),
        ]);
    }


}
