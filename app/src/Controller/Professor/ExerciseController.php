<?php

namespace App\Controller\Professor;

use App\Entity\Exercise;
use App\Entity\Group;
use App\Entity\Subject;
use App\Entity\User;
use App\Form\EditExerciseType;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Repository\GroupRepository;
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
    public function index(ExerciseRepository $exerciseRepository, GroupRepository $groupRepository): Response
    {
        $groups = $this->getUser()->getProfessorGroups();
        return $this->render('professor/exercise/index.html.twig', [
            'exercises' => $exerciseRepository->findBy(['subject' => $this->getUser()->getSubject()]),
            'groups' => $groups
        ]);
    }

    /**
     * @Route("/new/{id}", name="new", methods={"GET","POST"})
     */
    public function new(Request $request, ExerciseRepository $exerciseRepository, Subject $subject): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$exerciseRepository->checkNameExist($exercise->getName(), $exercise->getSubject())) {
                $entityManager = $this->getDoctrine()->getManager();
                $exercise->setSubject($subject);
                $entityManager->persist($exercise);
                $entityManager->flush();

                $this->addFlash('success', 'Exercise created successfully');

                return $this->redirectToRoute('professor_exercise_index', array('id' => $subject->getUsers()->getId()));
            }

            $this->addFlash('danger', 'The exercise is assigned for another subject !');
        }

        return $this->render('professor/exercise/new.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Exercise $exercise): Response
    {
        return $this->render('professor/exercise/show.html.twig', [
            'exercise' => $exercise,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Exercise $exercise): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Exercise updated successfully');

            return $this->redirectToRoute('professor_exercise_index');
        }

        return $this->render('professor/exercise/edit.html.twig', [
            'exercise' => $exercise,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Request $request, Exercise $exercise): Response
    {
        if ($this->isCsrfTokenValid('delete' . $exercise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exercise);
            $entityManager->flush();

            $this->addFlash('danger', 'Exrecise deleted successfully');
        }

        return $this->redirectToRoute('professor_exercise_index');
    }


    /**
     * @Route("/{group}/{exercise}", name="remove", methods={"DELETE"})
     */
    public function remove(Request $request, Group $group, Exercise $exercise): Response
    {
        if ($this->isCsrfTokenValid('remove' . $exercise->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $exercise->removeGroup($group);
            $entityManager->flush();

            $this->addFlash('danger', 'Exercise removed successfully');
        }
        return $this->redirectToRoute('professor_exercise_group_index', ['id' => $group->getId()]);
    }
}
