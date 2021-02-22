<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByRoles($role)
    {
        $user = $this->findAll();
        foreach ($user as $key => $value) {
            if (!in_array($role, $value->getRoles())) {
                unset($user[$key]);
            }
        }
        return $user;
    }

    public function studentsWithoutGroup($Students): ?array
    {
        foreach ($Students as $key => $value) {
            if ($value->getStudentGroup()) {
                unset($Students[$key]);
            }
        }
        return $Students;
    }

    public function professorsWithoutGroup($professors, $nbrGroup): ?array
    {
        foreach ($professors as $key => $value) {
            if (count($value->getProfessorGroups()->getValues()) === $nbrGroup) {
                unset($professors[$key]);
            }
        }
        return $professors;
    }
}
