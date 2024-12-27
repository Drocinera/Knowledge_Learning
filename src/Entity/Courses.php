<?php

namespace App\Entity;

use App\Repository\CoursesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: CoursesRepository::class)]
class Courses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: "course", targetEntity: Lessons::class, orphanRemoval: true)]
    private Collection $lessons;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP', 'on update' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255)]
    private ?string $updated_by = null;

    #[ORM\ManyToOne(targetEntity: Themes::class, inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Themes $theme = null;

    /**
     * Initializes a new instance of the Courses class.
     * Sets default values for `created_at` and `updated_at`.
     */
    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }

    /**
     * Gets the unique identifier of the course.
     *
     * @return int|null The course ID, .
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the lessons associated with the course.
     *
     * @return Collection<int, Lessons> A collection of lessons.
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    /**
     * Adds a lesson to the course.
     *
     * @param Lessons $lesson The lesson to add.
     * @return static The current instance for method chaining.
     */
    public function addLesson(Lessons $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons[] = $lesson;
            $lesson->setCourse($this);
        }

        return $this;
    }

    /**
     * Removes a lesson from the course.
     *
     * @param Lessons $lesson The lesson to remove.
     * @return static The current instance for method chaining.
     */
    public function removeLesson(Lessons $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // Set the owning side to null (unless already changed)
            if ($lesson->getCourse() === $this) {
                $lesson->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * Gets the name of the course.
     *
     * @return string|null The course name, .
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the course.
     *
     * @param string $name The course name.
     * @return static The current instance for method chaining.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the price of the course.
     *
     * @return string|null The course price .
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Sets the price of the course.
     *
     * @param string $price The course price.
     * @return static The current instance for method chaining.
     */
    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets the description of the course.
     *
     * @return string|null The course description .
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the course.
     *
     * @param string $description The course description.
     * @return static The current instance for method chaining.
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the creation timestamp of the course.
     *
     * @return \DateTimeImmutable|null The creation timestamp .
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Sets the creation timestamp of the course.
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
     * Gets the last update timestamp of the course.
     *
     * @return \DateTimeImmutable|null The last update timestamp .
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Sets the last update timestamp of the course.
     *
     * @param \DateTimeImmutable $updated_at The last update timestamp.
     * @return static The current instance for method chaining.
     */
    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Gets the user who created the course.
     *
     * @return string|null The username of the creator.
     */
    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    /**
     * Sets the user who created the course.
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
     * Gets the user who last updated the course.
     *
     * @return string|null The username of the last updater.
     */
    public function getUpdatedBy(): ?string
    {
        return $this->updated_by;
    }

    /**
     * Sets the user who last updated the course.
     *
     * @param string $updated_by The username of the last updater.
     * @return static The current instance for method chaining.
     */
    public function setUpdatedBy(string $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }

    /**
     * Gets the theme associated with the course.
     *
     * @return Themes|null The associated theme.
     */
    public function getTheme(): ?Themes
    {
        return $this->theme;
    }

    /**
     * Sets the theme associated with the course.
     *
     * @param Themes|null $theme The theme to associate.
     * @return static The current instance for method chaining.
     */
    public function setTheme(?Themes $theme): static
    {
        $this->theme = $theme;

        return $this;
    }
}
