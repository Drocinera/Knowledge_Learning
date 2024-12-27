<?php

namespace App\Repository;

use App\Entity\Lessons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Lessons` entity.
 * 
 * This class provides methods for interacting with the `Lessons` entity in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to facilitate common database operations.
 *
 * @extends ServiceEntityRepository<Lessons>
 */
class LessonsRepository extends ServiceEntityRepository
{
    /**
     * LessonsRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `Lessons` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lessons::class);
    }

    /**
     * Retrieve all lessons from the database.
     * 
     * This method retrieves all lessons stored in the database.
     * It uses the inherited `findAll` method from `ServiceEntityRepository` to fetch all `Lessons` entities.
     *
     * @return array An array of `Lessons` entities.
     */
    public function findAllLessons()
    {
        return $this->findAll();
    }
}
