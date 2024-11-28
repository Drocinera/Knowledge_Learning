<?php

namespace App\Repository;

use App\Entity\Comprise;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comprise>
 */
class CompriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comprise::class);
    }

    public function hasAccess(Users $user, string $type, int $itemId): bool
    {
        $qb = $this->createQueryBuilder('c')
            ->join('c.purchase', 'p')
            ->where('p.user = :user')
            ->andWhere('c.access_granted = true');

        if ($type === 'course') {
            $qb->andWhere('c.course = :itemId');
        } elseif ($type === 'lesson') {
            // Vérifier si l'accès est donné à la leçon individuellement ou via un cursus
            $qb->leftJoin('c.course', 'course')
            ->leftJoin('course.lessons', 'lesson')
            ->andWhere('(c.lesson = :itemId OR lesson.id = :itemId)');
    }

        $qb->setParameter('user', $user)
           ->setParameter('itemId', $itemId);

        return (bool) $qb->getQuery()->getOneOrNullResult();
    }


    //    /**
    //     * @return Comprise[] Returns an array of Comprise objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comprise
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
