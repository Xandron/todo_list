<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByFilters($status, $priorityFrom, $priorityTo, $title, $sortBy, $sortOrder): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($status) {
            $qb->andWhere('t.status = :status')
                ->setParameter('status', $status);
        }

        if ($priorityFrom && $priorityTo) {
            $qb->andWhere('t.priority BETWEEN :priorityFrom AND :priorityTo')
                ->setParameter('priorityFrom', $priorityFrom)
                ->setParameter('priorityTo', $priorityTo);
        }

        if ($title) {
            $qb->andWhere($qb->expr()->like('t.title', ':title'))
                ->setParameter('title', '%'.$title.'%');
        }

        if ($sortBy && $sortOrder) {
            $qb->orderBy('t.'.$sortBy, $sortOrder);
        }

        return $qb->getQuery()->getResult();
    }

    public function countIncompleteTasks(): int
    {
        $qb = $this->createQueryBuilder('t');
        $qb->select('COUNT(t)')
            ->where('t.status = :status')
            ->setParameter('status', 'todo');

        return $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
