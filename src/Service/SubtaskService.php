<?php

namespace App\Service;

use App\Dto\SubtaskDto;
use App\Entity\Subtask;
use App\Entity\Task;
use App\Repository\SubtaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class SubtaskService
{
    private EntityManagerInterface $entityManager;
    private SubtaskRepository $subtaskRepository;

    public function __construct(EntityManagerInterface $entityManager, SubtaskRepository $subtaskRepository)
    {
        $this->entityManager = $entityManager;
        $this->subtaskRepository = $subtaskRepository;
    }

    public function create(SubtaskDto $subtaskDto, Task $task): Subtask
    {
        $subtask = new Subtask();
        $subtask->setTitle($subtaskDto->title);
        $subtask->setDescription($subtaskDto->description);
        $subtask->setStatus($subtaskDto->status);
        $subtask->setCreateAtValue();
        $subtask->setUpdateAtValue();

        $task->addSubtask($subtask);

        $this->entityManager->persist($subtask);
        $this->entityManager->flush();

        return $subtask;
    }

    public function update(SubtaskDto $subtaskDto, mixed $subtaskId): Subtask
    {
        $subtask = $this->subtaskRepository->find($subtaskId);

        $subtask->setTitle($subtaskDto->title);
        $subtask->setDescription($subtaskDto->description);
        $subtask->setStatus($subtaskDto->status);

        $this->entityManager->flush();

        return $subtask;
    }

    public function delete(Task $task, mixed $subtaskId): void
    {
        $subtask = $this->subtaskRepository->find($subtaskId);
        $task->removeSubtask($subtask);

        $this->entityManager->remove($subtask);
        $this->entityManager->flush();
    }

    public function complete(int $subtaskId): void
    {
        $task = $this->subtaskRepository->find($subtaskId);

        $task->setCompletionTime(new DateTimeImmutable());
        $task->setUpdateAtValue();

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}