<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
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
                    ->orderBy('j.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return array
     */
    public function findAllOrderByNotExpired(): array
    {

        return $this->createQueryBuilder('j')
                    ->where('j.activated = true')
                    ->andWhere('j.expiresAt > :nowDate')
                    ->orderBy('j.createdAt', 'ASC')
                    ->setParameter('nowDate', new \DateTime())
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param $id
     *
     * @return Job|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findActiveJob(int $id): ?Job
    {
        return $this->createQueryBuilder('j')
                    ->where('j.activated = true')
                    ->andWhere('j.id = :id')
                    ->setParameter('id', $id)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function getPaginatedActiveJobsByCategoryQuery(
        Category $category
    ): AbstractQuery {
        return $this->createQueryBuilder('j')
                    ->where('j.category = :category')
                    ->andWhere('j.expiresAt > :date')
                    ->andWhere('j.activated = true')
                    ->setParameter('category', $category)
                    ->setParameter('date', new \DateTime())
                    ->getQuery();
    }

}
