<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Users` entity.
 * 
 * This class provides methods for interacting with the `Users` entity in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to facilitate common database operations.
 *
 * @extends ServiceEntityRepository<Users>
 */

class UsersRepository extends ServiceEntityRepository
{
    /**
     * UsersRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `USers` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }
}
