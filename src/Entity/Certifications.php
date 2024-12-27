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

    #[ORM\ManyToOne(targetEntity: Users::class, inversedBy: 'certifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $date_awarded = null;

    /**
     * Gets the ID of the certification.
     *
     * @return int|null The certification ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the user associated with the certification.
     *
     * @return Users|null The user entity .
     */
    public function getUser(): ?Users
    {
        return $this->user;
    }

    /**
     * Sets the user associated with the certification.
     *
     * @param Users|null $user The user entity to associate.
     * @return self Returns the current instance for method chaining.
     */
    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the title of the certification.
     *
     * @return string|null The title of the certification .
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the certification.
     *
     * @param string $title The title of the certification.
     * @return self Returns the current instance for method chaining.
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the description of the certification.
     *
     * @return string|null The description .
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the certification.
     *
     * @param string|null $description The description of the certification.
     * @return self Returns the current instance for method chaining.
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the date the certification was awarded.
     *
     * @return \DateTimeImmutable|null The award date .
     */
    public function getDateAwarded(): ?\DateTimeImmutable
    {
        return $this->date_awarded;
    }

    /**
     * Sets the date the certification was awarded.
     *
     * @param \DateTimeImmutable $date_awarded The date the certification was awarded.
     * @return self Returns the current instance for method chaining.
     */
    public function setDateAwarded(\DateTimeImmutable $date_awarded): self
    {
        $this->date_awarded = $date_awarded;

        return $this;
    }
}
