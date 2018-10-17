<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @throws NonUniqueResultException
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        $qb = $this->createQueryBuilder('u');

        return $qb
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->eq('u.username', ':value'),
                        $qb->expr()->eq('u.email', ':value')
                    ),
                    $qb->expr()->eq('u.isActive', '1')
                )
            )
            ->setParameter('value', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
