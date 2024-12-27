<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private ?bool $is_active = false;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP', 'on update' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255)]
    private ?string $updated_by = null;

    /**
     * List of certifications associated with the user.
     *
     * @var Collection<int, Certifications>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Certifications::class, orphanRemoval: true)]
    private Collection $certifications;

    /**
     * Callback executed before each update to set `updated_at` and `updated_by`.
     */
    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updated_at = new \DateTimeImmutable();
        $this->updated_by = 'current_email'; 
    }

    /**
     * Returns the unique user ID.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Returns the user ID (email).
     *
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Return the user's email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Defines the user's email.
     *
     * @param string|null $email
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Returns user roles.
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->role ? [$this->role->getName()] : ['ROLE_USER'];
    }

    /**
     * Defines the user role.
     *
     * @param Role $role
     * @return self
     */
    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * Returns the hashed password.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set hashed password.
     *
     * @param string $password
     * @return self
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Returns `null` (no salt required for bcrypt).
     *
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive user information.
     */
    public function eraseCredentials(): void
    {
    }

    /**
     *Returns if user is active.
     *
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    /**
     * Sets the active status of the user.
     *
     * @param bool $is_active
     * @return self
     */
    public function setActive(bool $is_active): static
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * Returns the creation date of the entity.
     *
     * @return \DateTimeImmutable|null The creation date or null if not set.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Defines the creation date of the entity.
     *
     * @param \DateTimeImmutable $created_at The date of creation.
     * @return self Returns the current instance to enable chaining.
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Returns the last update date of the entity.
     *
     * @return \DateTimeImmutable|null The update date or null if not set.
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Defines the last update date of the entity.
     *
     * @param \DateTimeImmutable $updated_at The update date.
     * @return self Returns the current instance to enable chaining.
     */
    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Returns the identifier or email of the person who created the entity.
     *
     * @return string|null The creator's identifier or email or null if not defined.
     */
    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    /**
     * Defines the identifier or email of the person who created the entity.
     *
     * @param string $created_by The identifier or email of the creator.
     * @return self Returns the current instance to enable chaining.
     */
    public function setCreatedBy(string $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Returns the ID or email of the person who last updated the entity.
     *
     * @return string|null The publisher's identifier or email or null if not defined.
     */
    public function getUpdatedBy(): ?string
    {
        return $this->updated_by;
    }

    /**
     *Defines the username or email of the person who last updated the entity.
     *
     * @param string $updated_by The publisher's identifier or email.
     * @return self Returns the current instance to enable chaining.
     */
    public function setUpdatedBy(string $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }

    /**
     * Callback executed before insert to initialize default values.
     */
    #[ORM\PrePersist]
    public function prePersist(): void
    {
        if (null === $this->created_by) {
            $this->created_by = (string) $this->getId();
        }

        if (null === $this->updated_by) {
            $this->updated_by = (string) $this->getId();
        }

        if (null === $this->created_at) {
            $this->created_at = new \DateTimeImmutable();
        }

        if (null === $this->updated_at) {
            $this->updated_at = new \DateTimeImmutable();
        }
    }

    /**
     * Constructor to initialize collections and default values.
     */
    public function __construct()
    {
        $this->certifications = new ArrayCollection();
    }

    /**
     * Returns the collection of certifications associated with the user.
     *
     * @return Collection<int, Certifications> The Certifications object collection.
     */
    public function getCertifications(): Collection
    {
        return $this->certifications;
    }

    /**
     * Adds a certification to the collection.
     *
     * @param Certifications $certification The Certification object to add.
     * @return self Returns the current instance to enable chaining.
     */
    public function addCertification(Certifications $certification): self
    {
        if (!$this->certifications->contains($certification)) {
            $this->certifications->add($certification);
            $certification->setUser($this);
        }

        return $this;
    }

    /**
     * Removes a certification from the collection.
     *
     * @param Certifications $certification The Certification object to delete.
     * @return self Returns the current instance to enable chaining.
     */
    public function removeCertification(Certifications $certification): self
    {
        if ($this->certifications->removeElement($certification)) {
            // Disconnects the relationship on the Certifications side
            if ($certification->getUser() === $this) {
                $certification->setUser(null);
            }
        }

        return $this;
    }

}
