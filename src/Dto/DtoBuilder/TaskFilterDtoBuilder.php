<?php

namespace App\Dto\DtoBuilder;

use App\Dto\TaskFilterDto;
use App\Enum\TaskFilters;

final class TaskFilterDtoBuilder
{
    public static function build(array $data): TaskFilterDto
    {
        $taskFilterDto = new TaskFilterDto();
        $taskFilterDto->status = $data[TaskFilters::Status->name];
        $taskFilterDto->priorityFrom = $data[TaskFilters::PriorityFrom->name];
        $taskFilterDto->priorityTo = $data[TaskFilters::PriorityTo->name];
        $taskFilterDto->title = $data[TaskFilters::Title->name];
        $taskFilterDto->sortBy = $data[TaskFilters::SortBy->name];
        $taskFilterDto->sortOrder = $data[TaskFilters::SortOrder->name];

        return $taskFilterDto;
    }
}