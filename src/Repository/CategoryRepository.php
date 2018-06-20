<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * CategoryRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return array
     */
    public function findAllWithJobsActiveNotExpiredWithOrderByField(): array
    {
        return $this->createQueryBuilder('c')
                    ->select('c, j')
                    ->innerJoin('c.jobs', 'j')
                    ->where('j.activated = true')
                    ->andWhere('j.expiresAt > :nowDate')
                    ->orderBy('c.name', 'ASC')
                    ->setParameter('nowDate', new \DateTime())
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param $slug
     *
     * @return Category|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function findBySlugWithActiveJobsNotExpired($slug): ?Category
    {
        return $this->createQueryBuilder('c')
                    ->select('c, j')
                    ->join('c.jobs', 'j')
                    ->where('j.activated = true')
                    ->andWhere('j.expiresAt > :nowDate')
                    ->andWhere('c.slug = :slug')
                    ->setParameter('nowDate', new \DateTime())
                    ->setParameter('slug', $slug)
                    ->orderBy('j.createdAt', 'DESC')
                    ->getQuery()
                    ->getSingleResult();
    }
}
