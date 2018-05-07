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
     * @param $field
     * @param $order
     *
     * @return array
     */
    public function findAllWithJobsActiveNotExpiredWithOrderByField(
        $field,
        $order
    ): array {
        if (\in_array($field, [
                'location',
                'company',
                'position',
                'createdAt',
            ])
            && \in_array($order, [
                'ASC',
                'DESC',
            ])
        ) {
            return $this->createQueryBuilder('c')
                        ->select('c, j')
                        ->innerJoin('c.jobs', 'j')
                        ->where('j.activated = true')
                        ->andWhere('j.expiresAt > :nowDate')
                        ->orderBy('j.' . $field, $order)
                        ->setParameter('nowDate', new \DateTime())
                        ->getQuery()
                        ->getResult();
        }

        return [];
    }

    /**
     * @param $slug
     *
     * @return array
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
                    ->getQuery()
                    ->getSingleResult();
    }

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
