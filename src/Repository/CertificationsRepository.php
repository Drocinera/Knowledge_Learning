<?php

namespace App\Repository;

use App\Entity\Certifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Certifications` entity.
 * 
 * This class is responsible for querying and interacting with the `Certifications` entities in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to simplify common database operations.
 *
 * @extends ServiceEntityRepository<Certifications>
 */
class CertificationsRepository extends ServiceEntityRepository
{
    /**
     * CertificationsRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `Certifications` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certifications::class);
    }
}
