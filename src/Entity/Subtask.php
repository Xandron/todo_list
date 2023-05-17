<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use App\Repository\SubtaskRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubtaskRepository::class)]
class Subtask
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read'])]
    #[Assert\NotBlank]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    #[Assert\Type("string")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read'])]
    #[Assert\Type("integer")]
    #[Assert\NotBlank()]
    private ?int $status = null;

    #[ORM\ManyToOne(inversedBy: 'subtasks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Task $task = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read'])]
    private ?DateTimeInterface $completionTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $createAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updateAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        if (!TaskStatus::tryFrom($status)) {
            throw new InvalidArgumentException('Invalid task status');
        }

        $this->status = $status;

        return $this;
    }

    public function getCompletionTime(): ?DateTimeInterface
    {
        return $this->completionTime;
    }

    public function setCompletionTime(?DateTimeInterface $completionTime): self
    {
        $this->completionTime = $completionTime;

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreateAtValue(): void
    {
        $this->createAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdateAtValue(): void
    {
        $this->updateAt = new DateTimeImmutable();
    }

    public function getCreateAt(): DateTimeInterface
    {
        return $this->createAt;
    }

    public function getUpdateAt(): DateTimeInterface
    {
        return $this->updateAt;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }


}
