<?php

namespace App\Service;

use App\Dto\TaskDto;
use App\Dto\TaskFilterDto;
use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class TaskService
{
    private SerializerInterface $serializer;
    private TaskRepository $taskRepository;

    private EntityManagerInterface $entityManager;


    public function __construct(
        SerializerInterface $serializer,
        TaskRepository $taskRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->serializer = $serializer;
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function get(TaskFilterDto $filterDto): array
    {
        $tasks = $this->taskRepository->findByFilters(
            $filterDto->status,
            $filterDto->priorityFrom,
            $filterDto->priorityTo,
            $filterDto->title,
            $filterDto->sortBy,
            $filterDto->sortOrder
        );

        return $this->serializer->normalize($tasks);
    }

    public function create(TaskDto $taskDto): Task
    {
        $task = new Task();
        $task->setTitle($taskDto->title);
        $task->setDescription($taskDto->description);
        $task->setStatus($taskDto->status);
        $task->setPriority($taskDto->priority);
        $task->setCreateAtValue();
        $task->setUpdateAtValue();

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function update(TaskDto $taskDto, Task $task): Task
    {
        $task->setTitle($taskDto->title);
        $task->setDescription($taskDto->description);
        $task->setPriority($taskDto->priority);
        $task->setStatus($taskDto->status);

        $this->entityManager->flush();

        return $task;
    }

    public function delete(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function complete(int $taskId): void
    {
        $task = $this->taskRepository->find($taskId);

        $task->setCompletionTime(new DateTimeImmutable());
        $task->setUpdateAtValue();

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }
}