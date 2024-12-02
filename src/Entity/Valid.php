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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getLesson(): ?Lessons
    {
        return $this->lesson;
    }

    public function setLesson(?Lessons $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getDateValidated(): ?\DateTimeImmutable
    {
        return $this->date_validated;
    }

    public function setDateValidated(?\DateTimeImmutable $date_validated): self
    {
        $this->date_validated = $date_validated;

        return $this;
    }
}
