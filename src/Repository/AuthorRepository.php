<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 *
 * @method Author|null find($id, $lockMode = null, $lockVersion = null)
 * @method Author|null findOneBy(array $criteria, array $orderBy = null)
 * @method Author[]    findAll()
 * @method Author[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function save(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Author $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTopAuthorsLastWeek(int $limit = 3): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('a.id, a.firstName, a.lastName, COUNT(article) as articlesCount')
            ->join('a.articles', 'article')
            ->where('article.createdAt >= :lastWeek')
            ->setParameter('lastWeek', new \DateTime('-1 week'))
            ->groupBy('a.id')
            ->orderBy('articlesCount', 'DESC')
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }
}
