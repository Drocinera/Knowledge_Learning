<?php

namespace App\Entity;

use App\Repository\LessonsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
class Lessons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Courses::class, inversedBy: "lessons")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Courses $course = null; 

    #[ORM\OneToMany(mappedBy: 'lesson', targetEntity: Comprise::class)]
    private Collection $comprises;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $video_url = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP', 'on update' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $created_by = null;

    #[ORM\Column(length: 255)]
    private ?string $updated_by = null;

    /**
     * Initializes a new instance of the Lessons class.
     * Sets default values for `created_at` and `updated_at`.
     */
    public function __construct()
    {
        $this->comprises = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable(); 
        $this->updated_at = new \DateTimeImmutable();
    }

    /**
     * Gets the unique identifier of the lesson.
     *
     * @return int|null The lesson ID .
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the course associated with the lesson.
     *
     * @return Courses|null The associated course .
     */
    public function getCourse(): ?Courses
    {
        return $this->course;
    }

    /**
     * Sets the course associated with the lesson.
     *
     * @param Courses|null $course The associated course.
     * @return static The current instance for method chaining.
     */
    public function setCourse(?Courses $course): static
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Gets the collection of comprises (related entities) for the lesson.
     *
     * @return Collection<int, Comprise> A collection of comprises.
     */
    public function getComprises(): Collection
    {
        return $this->comprises;
    }

    /**
     * Adds a comprise to the lesson.
     *
     * @param Comprise $comprise The comprise entity to add.
     * @return static The current instance for method chaining.
     */
    public function addComprise(Comprise $comprise): static
    {
        if (!$this->comprises->contains($comprise)) {
            $this->comprises->add($comprise);
            $comprise->setLesson($this);
        }

        return $this;
    }

    /**
     * Removes a comprise from the lesson.
     *
     * @param Comprise $comprise The comprise entity to remove.
     * @return static The current instance for method chaining.
     */
    public function removeComprise(Comprise $comprise): static
    {
        if ($this->comprises->removeElement($comprise)) {
            // Disconnect the relationship on the Comprise side
            if ($comprise->getLesson() === $this) {
                $comprise->setLesson(null);
            }
        }

        return $this;
    }

    /**
     * Gets the name of the lesson.
     *
     * @return string|null The lesson name.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name of the lesson.
     *
     * @param string $name The lesson name.
     * @return static The current instance for method chaining.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the description of the lesson.
     *
     * @return string|null The lesson description.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Sets the description of the lesson.
     *
     * @param string $description The lesson description.
     * @return static The current instance for method chaining.
     */
    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Gets the video URL for the lesson.
     *
     * @return string|null The video URL.
     */
    public function getVideoUrl(): ?string
    {
        return $this->video_url;
    }

    /**
     * Sets the video URL for the lesson.
     *
     * @param string $video_url The video URL.
     * @return static The current instance for method chaining.
     */
    public function setVideoUrl(string $video_url): static
    {
        $this->video_url = $video_url;

        return $this;
    }

    /**
     * Gets the price of the lesson.
     *
     * @return string|null The lesson price.
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Sets the price of the lesson.
     *
     * @param string $price The lesson price.
     * @return static The current instance for method chaining.
     */
    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets the creation timestamp of the lesson.
     *
     * @return \DateTimeImmutable|null The creation timestamp.
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Sets the creation timestamp of the lesson.
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
     * Gets the last update timestamp of the lesson.
     *
     * @return \DateTimeImmutable|null The last update timestamp.
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Sets the last update timestamp of the lesson.
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
     * Gets the username of the creator of the lesson.
     *
     * @return string|null The creator's username.
     */
    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    /**
     * Sets the username of the creator of the lesson.
     *
     * @param string $created_by The creator's username.
     * @return static The current instance for method chaining.
     */
    public function setCreatedBy(string $created_by): static
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * Gets the username of the last updater of the lesson.
     *
     * @return string|null The last updater's username.
     */
    public function getUpdatedBy(): ?string
    {
        return $this->updated_by;
    }

    /**
     * Sets the username of the last updater of the lesson.
     *
     * @param string $updated_by The last updater's username.
     * @return static The current instance for method chaining.
     */
    public function setUpdatedBy(string $updated_by): static
    {
        $this->updated_by = $updated_by;

        return $this;
    }
}
