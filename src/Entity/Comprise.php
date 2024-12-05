<?php

namespace App\Entity;

use App\Repository\CompriseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompriseRepository::class)]
class Comprise {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // ID auto-généré


    #[ORM\ManyToOne(targetEntity: Courses::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Courses $course = null;

    #[ORM\ManyToOne(targetEntity: Purchases::class)]
    private ?Purchases $purchase = null;

    #[ORM\ManyToOne(targetEntity: Lessons::class, inversedBy: 'comprises')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Lessons $lesson = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?bool $access_granted = null;

    public function getCourse(): ?Courses
    {
        return $this->course;
    }

        public function setCourse(?Courses $course): static
    {
        $this->course = $course;
        return $this;
    }

    public function getPurchase(): ?Purchases
    {
        return $this->purchase;
    }

    public function setPurchase(?Purchases $purchase): static
    {
        $this->purchase = $purchase;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }


    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getAccessGranted(): ?bool
    {
        return $this->access_granted;
    }


    public function setAccessGranted(bool $access_granted): static
    {
        $this->access_granted = $access_granted;
        return $this;
    }

    public function setLesson(?Lessons $lesson): self
    {
        $this->lesson = $lesson;
        return $this;
    }

    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }
}

class CompriseId
{
    private ?Courses $course = null;
    private ?Purchases $purchase = null;

    // Méthodes pour égalité/hashage des objets (Doctrine en a besoin)
    public function equals(object $other): bool
    {
        return $other instanceof self &&
            $this->course === $other->course &&
            $this->purchase === $other->purchase;
    }

    public function __hash(): int
    {
        return spl_object_id($this->course) ^ spl_object_id($this->purchase);
    }
}

