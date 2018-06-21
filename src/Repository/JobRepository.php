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
                    ->orderBy('j.createdAt', 'DESC')
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
                    ->orderBy('j.createdAt', 'DESC')
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

    /**
     * @param $rawQuery
     *
     * @return AbstractQuery
     */
    public function getPaginatedActiveJobsBySearchQuery($rawQuery
    ): AbstractQuery {
        $query       = $this->sanitizeSearchQuery($rawQuery);
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return $this->createQueryBuilder('j')
                        ->andWhere('1 != 1')
                        ->getQuery();
        }

        $queryBuilder = $this->createQueryBuilder('j');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                ->andWhere('j.activated = true
                AND j.expiresAt > :nowDate
                AND (j.description LIKE :t_'.$key.
                ' OR j.location LIKE :t_'.$key.
                ' OR j.position LIKE :t_'.$key.
                ' OR j.company LIKE :t_'.$key.')')
                ->setParameter('nowDate', new \DateTime())
                ->setParameter('t_'.$key, '%'.$term.'%');
        }

        return $queryBuilder
            ->orderBy('j.createdAt', 'DESC')
            ->getQuery();
    }

    /**
     * Removes all non-alphanumeric characters except whitespaces.
     */
    private function sanitizeSearchQuery(string $query): string
    {
        return trim(preg_replace('/[[:space:]]+/', ' ', $query));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array
    {
        $terms = array_unique(explode(' ', $searchQuery));

        return array_filter(
            $terms,
            function ($term) {
                return 2 <= mb_strlen($term);
            }
        );
    }

}
