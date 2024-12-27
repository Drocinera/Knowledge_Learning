<?php

namespace App\Entity;

use App\Repository\CompriseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompriseRepository::class)]
class Comprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null; // Auto-generated ID

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

    /**
     * Gets the course associated with the record.
     *
     * @return Courses|null The associated course.
     */
    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    /**
     * Sets the course associated with the record.
     *
     * @param Courses|null $course The course to associate.
     * @return static Returns the current instance for method chaining.
     */
    public function setCourse(?Courses $course): static
    {
        $this->course = $course;
        return $this;
    }

    /**
     * Gets the purchase associated with the record.
     *
     * @return Purchases|null The associated purchase.
     */
    public function getPurchase(): ?Purchases
    {
        return $this->purchase;
    }

    /**
     * Sets the purchase associated with the record.
     *
     * @param Purchases|null $purchase The purchase to associate.
     * @return static Returns the current instance for method chaining.
     */
    public function setPurchase(?Purchases $purchase): static
    {
        $this->purchase = $purchase;
        return $this;
    }

    /**
     * Gets the price of the record.
     *
     * @return string|null The price.
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Sets the price of the record.
     *
     * @param string $price The price value.
     * @return static Returns the current instance for method chaining.
     */
    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Checks if access is granted for the record.
     *
     * @return bool|null True if access is granted, false otherwise, .
     */
    public function getAccessGranted(): ?bool
    {
        return $this->access_granted;
    }

    /**
     * Sets whether access is granted for the record.
     *
     * @param bool $access_granted True to grant access, false otherwise.
     * @return static Returns the current instance for method chaining.
     */
    public function setAccessGranted(bool $access_granted): static
    {
        $this->access_granted = $access_granted;
        return $this;
    }

    /**
     * Gets the lesson associated with the record.
     *
     * @return Lessons|null The associated lesson.
     */
    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }

    /**
     * Sets the lesson associated with the record.
     *
     * @param Lessons|null $lesson The lesson to associate.
     * @return self Returns the current instance for method chaining.
     */
    public function setLesson(?Lessons $lesson): self
    {
        $this->lesson = $lesson;
        return $this;
    }
}

/**
 * Class CompriseId
 * Represents the composite key for the Comprise entity.
 */
class CompriseId
{
    private ?Courses $course = null;
    private ?Purchases $purchase = null;

    /**
     * Checks if the given object is equal to the current one.
     *
     * @param object $other The object to compare.
     * @return bool True if equal, false otherwise.
     */
    public function equals(object $other): bool
    {
        return $other instanceof self &&
            $this->course === $other->course &&
            $this->purchase === $other->purchase;
    }

    /**
     * Computes a hash for the current composite key.
     *
     * @return int A hash value for the composite key.
     */
    public function __hash(): int
    {
        return spl_object_id($this->course) ^ spl_object_id($this->purchase);
    }
}
