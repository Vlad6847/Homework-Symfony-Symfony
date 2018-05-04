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
                    ->orderBy('j.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param string $field
     *
     * @param string $order
     *
     * @return array
     */
    public function findAllOrderByNotExpired($field, $order): array
    {

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
            return $this->createQueryBuilder('j')
                        ->where('j.activated = true')
                        ->andWhere('j.expiresAt > :nowDate')
                        ->orderBy('j.'.$field, $order)
                        ->setParameter('nowDate', new \DateTime())
                        ->getQuery()
                        ->getResult();
        }

        return [];
    }

    public function findAllByPositionOrderBy($position, $order): array
    {
        return $this->createQueryBuilder('j')
                    ->andWhere('j.activated = true')
                    ->andWhere('j.position like %:position % ')
                    ->orderBy('j.'.$position. ', :order')
                    ->setParameters([
                        'position' => $position,
                        'order' => $order,
                    ])
                    ->getQuery()
                    ->getResult();

    }

}
