<?php

namespace App\Entity;

use App\Repository\PurchasesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchasesRepository::class)]
class Purchases
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $purchase_date = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    /**
     * Gets the unique identifier of the purchase.
     *
     * @return int|null The purchase ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the date and time when the purchase was made.
     *
     * @return \DateTimeImmutable|null The purchase date.
     */
    public function getPurchaseDate(): ?\DateTimeImmutable
    {
        return $this->purchase_date;
    }

    /**
     * Sets the date and time when the purchase was made.
     *
     * @param \DateTimeImmutable $purchase_date The purchase date.
     * @return static The current instance for method chaining.
     */
    public function setPurchaseDate(\DateTimeImmutable $purchase_date): static
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }

    /**
     * Initializes a new instance of the Purchases class.
     * Sets the `purchase_date` to the current date and time by default.
     */
    public function __construct()
    {
        $this->purchase_date = new \DateTimeImmutable();
    }

    /**
     * Gets the user associated with the purchase.
     *
     * @return Users|null The associated user.
     */
    public function getUser(): ?Users
    {
        return $this->user;
    }

    /**
     * Sets the user associated with the purchase.
     *
     * @param Users|null $user The associated user.
     * @return static The current instance for method chaining.
     */
    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }
}
