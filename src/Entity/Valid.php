<?php

namespace App\Entity;

use App\Repository\ValidRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValidRepository::class)]
#[ORM\Table(name: 'valid')]
class Valid
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Lessons::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lessons $lesson = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $date_validated = null;

    /**
     * Gets the user associated with this validation.
     *
     * @return Users|null The user entity .
     */
    public function getUser(): ?Users
    {
        return $this->user;
    }

    /**
     * Sets the user associated with this validation.
     *
     * @param Users|null $user The user entity.
     * @return self The current instance for method chaining.
     */
    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Gets the lesson associated with this validation.
     *
     * @return Lessons|null The lesson entity .
     */
    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }

    /**
     * Sets the lesson associated with this validation.
     *
     * @param Lessons|null $lesson The lesson entity.
     * @return self The current instance for method chaining.
     */
    public function setLesson(?Lessons $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }

    /**
     * Gets the date when the validation occurred.
     *
     * @return \DateTimeImmutable|null The date of validation .
     */
    public function getDateValidated(): ?\DateTimeImmutable
    {
        return $this->date_validated;
    }

    /**
     * Sets the date when the validation occurred.
     *
     * @param \DateTimeImmutable|null $date_validated The validation date.
     * @return self The current instance for method chaining.
     */
    public function setDateValidated(?\DateTimeImmutable $date_validated): self
    {
        $this->date_validated = $date_validated;

        return $this;
    }
}
