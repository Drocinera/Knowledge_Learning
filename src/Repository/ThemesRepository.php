<?php

namespace App\Repository;

use App\Entity\Themes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository class for managing the `Theles` entity.
 * 
 * This class provides methods for interacting with the `Themes` entity in the database.
 * It extends the `ServiceEntityRepository` class provided by Doctrine to facilitate common database operations.
 *
 * @extends ServiceEntityRepository<Themes>
 */

class ThemesRepository extends ServiceEntityRepository
{
    /**
     * ThemesRepository constructor.
     * 
     * The constructor accepts a `ManagerRegistry` which is used to access the Doctrine Entity Manager
     * and configure the repository for the `Themes` entity.
     *
     * @param ManagerRegistry $registry The registry that holds the Doctrine manager.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Themes::class);
    }

    public function findAllFormations()
    {
        return $this->findAll();
    }
}
