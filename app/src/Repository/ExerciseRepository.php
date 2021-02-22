<?php

namespace App\Repository;

use App\Entity\Exercise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exercise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exercise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exercise[]    findAll()
 * @method Exercise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExerciseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercise::class);
    }

    // /**
    //  * @return Exercise[] Returns an array of Exercise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /**
     * @return User[] Returns an array of User objects
     */
    public function checkNameExist($name, $subject)
    {
        $exercise = $this->findBy(['subject' => $subject]);
        $bool = false;
        foreach ($exercise as $key => $value) {
            if ($name === $value->getName()) {
                $bool = true;
            }
        }
        return $bool;
    }

    public function exercisesWithoutGroup($exercises, $nbrGroup): ?array
    {
        foreach ($exercises as $key => $value) {
            if (count($value->getGroups()->getValues()) === $nbrGroup) {
                unset($exercises[$key]);
            }
        }
        return $exercises;
    }

    public function getBySubjectAndStudent($student, $subject): ?array
    {
       $exercises = $this->findAll();
        foreach ($exercises as $key => $value) {
            if (!$value->getGroups()->contains($student->getStudentGroup()) || $value->getSubject() != $subject) {
                unset($exercises[$key]);
            }
        }
        return $exercises;
    }

    public function getExercisesBySubject($exercises){
        $newArr = array();
        foreach($exercises as $val){
            $newArr[$val->getSubject()->getName()][] = $val;
        }
        return $newArr;
    }



    /*
    public function findOneBySomeField($value): ?Exercise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
