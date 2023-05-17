<?php

namespace App\Repository;

use App\Entity\Subtask;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subtask>
 *
 * @method Subtask|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subtask|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subtask[]    findAll()
 * @method Subtask[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubtaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subtask::class);
    }

    public function save(Subtask $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subtask $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
