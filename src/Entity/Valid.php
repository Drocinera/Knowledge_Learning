<?php

namespace App\Entity;

use App\Repository\ValidRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValidRepository::class)]
class Valid {
    #[ORM\Id, ORM\ManyToOne(targetEntity: Users::class)]
    private ?Users $user = null;

    #[ORM\Id, ORM\ManyToOne(targetEntity: Lessons::class)]
    private ?Lessons $lesson = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $date_validated = null;
}
