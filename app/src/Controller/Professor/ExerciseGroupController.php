<?php

namespace App\Controller\Professor;

use App\Entity\Exercise;
use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exercise/group", name="exercise_group_")
 */
class ExerciseGroupController extends AbstractController
{
    /**
     * @Route("/{id}", name="index", methods={"GET"})
     */
    public function index(Group $group): Response
    {
        $exercises = $group->getExercises();
        return $this->render('professor/group_exercise/index.html.twig', [
            'exercises' => $exercises,
            'group' => $group,
        ]);
    }


    /**
     * @Route("/new/{exercise}/{group}/{professor}", name="new")
     */
    public function new(Exercise $exercise, Group $group, User $professor)
    {
        $em = $this->getDoctrine()->getManager();
        $group->addExercise($exercise);
        $em->flush();
        return $this->redirectToRoute('professor_exercise_index', array('id' => $professor->getId()));
    }
}
