<?php

namespace App\Repository;

use App\Entity\Valid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Valid` entity.
 * 
 * This class provides methods for interacting with the `Valid` entity in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to facilitate common database operations.
 *
 * @extends ServiceEntityRepository<Valid>
 */
class ValidRepository extends ServiceEntityRepository
{
    /**
     * ValidRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `Valid` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Valid::class);
    }
}
