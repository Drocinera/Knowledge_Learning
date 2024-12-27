<?php

namespace App\Repository;

use App\Entity\Comprise;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Comprise` entity.
 * 
 * This class is responsible for querying and interacting with the `Comprise` entities in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to simplify common database operations.
 *
 * @extends ServiceEntityRepository<Comprise>
 */
class CompriseRepository extends ServiceEntityRepository
{
    /**
     * CompriseRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `Comprise` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comprise::class);
    }

    /**
     * Check if a user has access to a specific course or lesson.
     * 
     * This method checks if a user has access to a specific course or lesson based on the type and item ID.
     * It uses the `purchase` relationship to ensure that the user has bought the item and checks whether 
     * access has been granted for the specified course or lesson.
     *
     * @param Users $user The user whose access needs to be checked.
     * @param string $type The type of the item ('course' or 'lesson').
     * @param int $itemId The ID of the course or lesson.
     *
     * @return bool True if access is granted, false otherwise.
     */
    public function hasAccess(Users $user, string $type, int $itemId): bool
    {
        // Create the query builder to build a custom query for checking access
        $qb = $this->createQueryBuilder('c')
            ->join('c.purchase', 'p') // Join with the `purchase` relation
            ->where('p.user = :user') // Filter by the specified user
            ->andWhere('c.access_granted = true'); // Ensure that access is granted

        // Check if the type is 'course' and filter accordingly
        if ($type === 'course') {
            $qb->andWhere('c.course = :itemId'); // Filter by the specific course
        } elseif ($type === 'lesson') {
            // For lessons, check if the access is granted to the lesson directly or through the course
            $qb->leftJoin('c.course', 'course') // Join with the `course` relation
            ->leftJoin('course.lessons', 'lesson') // Join with the `lessons` relation of the course
            ->andWhere('(c.lesson = :itemId OR lesson.id = :itemId)'); // Check if the lesson is part of the course
        }

        // Set the parameters for user and item ID
        $qb->setParameter('user', $user)
           ->setParameter('itemId', $itemId);

        // Execute the query and return true if access is granted, false otherwise
        return (bool) $qb->getQuery()->getOneOrNullResult();
    }
}
