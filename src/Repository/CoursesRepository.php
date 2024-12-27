<?php

namespace App\Repository;

use App\Entity\Courses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Courses` entity.
 * 
 * This class provides methods for interacting with the `Courses` entity in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to facilitate common database operations.
 *
 * @extends ServiceEntityRepository<Courses>
 */
class CoursesRepository extends ServiceEntityRepository
{
    /**
     * CoursesRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `Courses` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courses::class);
    }

    /**
     * Retrieve all courses with their associated themes.
     * 
     * This method retrieves all courses from the database and includes information about
     * their associated themes. It uses a `leftJoin` to fetch the `theme` relation for each course,
     * ensuring that even courses without an associated theme will be returned.
     *
     * @return array An array of `Courses` entities with their associated themes.
     */
    public function findAllCourses()
    {
        return $this->createQueryBuilder('c')
            // Left join with the `theme` relation to fetch the associated theme for each course
            ->leftJoin('c.theme', 't')
            // Add the `theme` entity to the result set to be included in the output
            ->addSelect('t')
            // Execute the query and return the result
            ->getQuery()
            ->getResult();
    }
}
