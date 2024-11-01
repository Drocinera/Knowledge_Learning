<?php

namespace App\Entity;

use App\Repository\CompriseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompriseRepository::class)]
class Comprise {
    #[ORM\Id, ORM\ManyToOne(targetEntity: Courses::class)]
    private ?Courses $course = null;

    #[ORM\Id, ORM\ManyToOne(targetEntity: Purchases::class)]
    private ?Purchases $purchase = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?bool $access_granted = null;
}