<?php

namespace App\Controller;

use App\Dto\DtoBuilder\SubtaskDtoBuilder;
use App\Dto\DtoBuilder\TaskDtoBuilder;
use App\Dto\DtoBuilder\TaskFilterDtoBuilder;
use App\Entity\Task;
use App\Enum\TaskFilters;
use App\Service\SubtaskService;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/task', name: 'api_task')]
class TaskController extends AbstractController
{
    private TaskService $taskService;
    private SubtaskService $subtaskService;

    public function __construct(TaskService $taskService, SubtaskService $subtaskService) {
        $this->taskService = $taskService;
        $this->subtaskService = $subtaskService;
    }

    #[Route('', methods: ['GET'])]
    public function getTasks(Request $request): JsonResponse
    {
        $filter = [
            TaskFilters::Status->name => $request->query->getInt(TaskFilters::Status->value),
            TaskFilters::PriorityFrom->name => $request->query->getInt(TaskFilters::PriorityFrom->value),
            TaskFilters::PriorityTo->name => $request->query->getInt(TaskFilters::PriorityTo->value),
            TaskFilters::Title->name => $request->query->get(TaskFilters::Title->value),
            TaskFilters::SortBy->name => $request->query->get(TaskFilters::SortBy->value),
            TaskFilters::SortOrder->name => $request->query->get(TaskFilters::SortOrder->value),
        ];

        $dto = TaskFilterDtoBuilder::build($filter);
        $result = $this->taskService->get($dto);

        return $this->json($result);
    }

    #[Route('', methods: ['POST'])]
    public function createTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = TaskDtoBuilder::build($data);
        $task = $this->taskService->create($dto);

        return $this->json($task, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function updateTask(Request $request, Task $task): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = TaskDtoBuilder::build($data);
        $task = $this->taskService->update($dto, $task);

        return $this->json($task);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteTask(Task $task): JsonResponse
    {
        $this->taskService->delete($task);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/subtasks', methods: ['POST'])]
    public function createSubtask(Request $request, Task $task): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = SubtaskDtoBuilder::build($data);
        $subtask = $this->subtaskService->create($dto, $task);

        return $this->json($subtask, Response::HTTP_CREATED);
    }

    #[Route('/{id}/subtasks/{subtaskId}', methods: ['PUT'])]
    public function updateSubtask(Request $request): JsonResponse
    {
        $subtaskId = $request->get('subtaskId');
        $data = json_decode($request->getContent(), true);

        $dto = SubtaskDtoBuilder::build($data);
        $subtask = $this->subtaskService->update($dto, $subtaskId);

        return $this->json($subtask);
    }

    #[Route('/{id}/subtasks/{subtaskId}', methods: ['DELETE'])]
    public function deleteSubtask(Request $request, Task $task): JsonResponse
    {
        $subtaskId = $request->get('subtaskId');
        $this->subtaskService->delete($task, $subtaskId);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
