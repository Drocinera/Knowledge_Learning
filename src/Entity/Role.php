<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * Gets the unique identifier of the role.
     *
     * @return int|null The role ID .
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the name of the role.
     *
     * @return string|null The role name .
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the role.
     *
     * @param string $name The role name.
     * @return static The current instance for method chaining.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the description of the role.
     *
     * @return string|null The role description .
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the role.
     *
     * @param string $description The role description.
     * @return static The current instance for method chaining.
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
