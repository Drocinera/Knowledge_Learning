<?php

namespace App\Entity;

use App\Repository\GetsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GetsRepository::class)]
class Gets {
    #[ORM\Id, ORM\ManyToOne(targetEntity: Users::class)]
    private ?Users $user = null;

    #[ORM\Id, ORM\ManyToOne(targetEntity: Certifications::class)]
    private ?Certifications $certification = null;
}

