<?php

namespace App\Dto\DtoBuilder;

use App\Dto\TaskDto;
use App\Enum\TaskFields;

final class TaskDtoBuilder
{
    public static function build(array $data): TaskDto
    {
        $taskDto = new TaskDto();
        $taskDto->title = $data[TaskFields::Title->value] ?? null;
        $taskDto->description = $data[TaskFields::Description->value] ?? null;
        $taskDto->status = $data[TaskFields::Status->value] ?? null;
        $taskDto->priority = $data[TaskFields::Priority->value] ?? null;

        return $taskDto;
    }
}