<?php

namespace App\Entity;

use App\Repository\ThemesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ThemesRepository::class)]
class Themes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: Courses::class)]
    private Collection $courses;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP', 'on update' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255)]
    private ?string $updated_by = null;

    /**
     * Gets the unique identifier of the theme.
     *
     * @return int|null The theme ID, .
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Initializes a new instance of the Themes class.
     */
    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    /**
     * Gets the collection of courses associated with this theme.
     *
     * @return Collection<int, Courses> The collection of courses.
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    /**
     * Adds a course to the theme.
     *
     * @param Courses $course The course to add.
     * @return static The current instance for method chaining.
     */
    public function addCourse(Courses $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setTheme($this);
        }

        return $this;
    }

    /**
     * Removes a course from the theme.
     *
     * @param Courses $course The course to remove.
     * @return static The current instance for method chaining.
     */
    public function removeCourse(Courses $course): static
    {
        if ($this->courses->removeElement($course)) {
            if ($course->getTheme() === $this) {
                $course->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * Gets the name of the theme.
     *
     * @return string|null The theme name .
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the theme.
     *
     * @param string $name The theme name.
     * @return static The current instance for method chaining.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the description of the theme.
     *
     * @return string|null The theme description .
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the theme.
     *
     * @param string $description The theme description.
     * @return static The current instance for method chaining.
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the image associated with the theme.
     *
     * @return string|null The image path or URL .
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Sets the image associated with the theme.
     *
     * @param string|null $image The image path or URL.
     * @return static The current instance for method chaining.
     */
    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Gets the creation date of the theme.
     *
     * @return \DateTimeImmutable|null The creation timestamp .
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Sets the creation date of the theme.
     *
     * @param \DateTimeImmutable $created_at The creation timestamp.
     * @return static The current instance for method chaining.
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Gets the last updated date of the theme.
     *
     * @return \DateTimeImmutable|null The update timestamp .
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Sets the last updated date of the theme.
     *
     * @param \DateTimeImmutable $updated_at The update timestamp.
     * @return static The current instance for method chaining.
     */
    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Gets the creator's username of the theme.
     *
     * @return string|null The username of the creator .
     */
    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    /**
     * Sets the creator's username of the theme.
     *
     * @param string $created_by The username of the creator.
     * @return static The current instance for method chaining.
     */
    public function setCreatedBy(string $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Gets the username of the last person who updated the theme.
     *
     * @return string|null The username of the updater .
     */
    public function getUpdatedBy(): ?string
    {
        return $this->updated_by;
    }

    /**
     * Sets the username of the last person who updated the theme.
     *
     * @param string $updated_by The username of the updater.
     * @return static The current instance for method chaining.
     */
    public function setUpdatedBy(string $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }
}
