<?php

namespace App\Entity;

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Task
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

    #[ORM\Column(nullable: true)]
    #[Groups(['write'])]
    #[Assert\Type("integer")]
    #[Assert\NotBlank()]
    private ?int $priority = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read'])]
    private ?DateTimeInterface $completionTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $createAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $updateAt = null;

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: Subtask::class)]
    private Collection $subtasks;
    
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

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        if (!TaskPriority::tryFrom($priority)) {
            throw new InvalidArgumentException('Invalid task priority');
        }

        $this->priority = $priority;

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

    /**
     * @return Collection<int, Subtask>
     */
    public function getSubtasks(): Collection
    {
        return $this->subtasks;
    }

    public function addSubtask(Subtask $subtask): self
    {
        if (!$this->subtasks->contains($subtask)) {
            $this->subtasks->add($subtask);
            $subtask->setTask($this);
        }

        return $this;
    }

    public function removeSubtask(Subtask $subtask): self
    {
        if ($this->subtasks->removeElement($subtask)) {
            // set the owning side to null (unless already changed)
            if ($subtask->getTask() === $this) {
                $subtask->setTask(null);
            }
        }

        return $this;
    }
}
