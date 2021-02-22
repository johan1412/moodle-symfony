<?php

namespace App\Controller\Student;

use App\Repository\ExerciseRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index", methods="GET")
     */
    public function index(ExerciseRepository $repository): Response
    {

       $exercisesBySubject = $repository->getExercisesBySubject($this->getUser()->getStudentGroup()->getExercises());
        return $this->render('student/index.html.twig', [
            'exercisesBySubject' => $exercisesBySubject,
        ]);
    }

}