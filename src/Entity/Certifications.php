<?php

namespace App\Entity;

use App\Repository\CertificationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationsRepository::class)]
class Certifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $date_awarded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAwarded(): ?\DateTimeImmutable
    {
        return $this->date_awarded;
    }

    public function setDateAwarded(\DateTimeImmutable $date_awarded): static
    {
        $this->date_awarded = $date_awarded;

        return $this;
    }
}
