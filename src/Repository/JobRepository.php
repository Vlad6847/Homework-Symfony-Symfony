<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
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

    /**
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('j')
                    ->andWhere('j.activated = true')
                    ->orderBy('j.expiresAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param $field
     *
     * @return array
     */
    public function findAllOrderBy($field = 'expiresAt'): array
    {
        if ($field === 'location' || $field === 'company'
            || $field === 'position'
            || $field === 'expiresAt'
            || $field === 'createdAt'
        ) {
            return $this->createQueryBuilder('j')
                        ->andWhere('j.activated = true')
                        ->orderBy('j.'.$field, 'ASC')
                        ->getQuery()
                        ->getResult();
        }

        return [];
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
