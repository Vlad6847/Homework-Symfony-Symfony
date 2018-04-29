<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    /**
     * JobRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Job::class);
    }

//    /**
//     * @return Job[] Returns an array of Job objects
//     */

    /**
     * @param $field
     *
     * @return mixed
     */
    public function findAllSortedBy($field)
    {
        return $this->createQueryBuilder('a')
                    ->orderBy('a.'.$field, 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function searchPosition($field)
    {
        return $this->createQueryBuilder('a')
                    ->andWhere('a.position = '.$field)
                    ->orderBy('a.'.$field, 'ASC')
                    ->getQuery()
                    ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Job
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
