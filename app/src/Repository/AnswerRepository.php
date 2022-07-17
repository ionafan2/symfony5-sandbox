<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Answer>
 *
 * @method Answer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Answer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Answer[]    findAll()
 * @method Answer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }

    public function add(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Answer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public static function createApprovedCriteria(): Criteria
    {
        return Criteria::create()->andWhere(
            Criteria::expr()->eq('status', Status::APPROVED)
        );
    }

    /**
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function findAllApproved(int $max = 10): array
    {
        return $this->createQueryBuilder('a')
            ->addCriteria(self::createApprovedCriteria())
            ->setMaxResults($max)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Answer[] Returns an array of Answer objects
     */
    public function findMostPopular(string $search = null): array
    {
        $queryBuilder = $this->createQueryBuilder('answer')
            ->addCriteria(self::createApprovedCriteria())
            ->orderBy('answer.votes', 'DESC')
            ->innerJoin('answer.question', 'question')
            ->addSelect('question');

        if ($search) {
            $queryBuilder->andWhere('answer.content LIKE :searchTerm OR question.question LIKE :searchTerm')
                ->setParameter('searchTerm', '%'.$search.'%');
        }

        return $queryBuilder
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
