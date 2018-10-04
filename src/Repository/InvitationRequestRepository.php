<?php

namespace App\Repository;

use App\Entity\InvitationRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InvitationRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvitationRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvitationRequest[]    findAll()
 * @method InvitationRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitationRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InvitationRequest::class);
    }

//    /**
//     * @return InvitationRequest[] Returns an array of InvitationRequest objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InvitationRequest
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
