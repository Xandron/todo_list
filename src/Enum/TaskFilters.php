<?php

namespace App\Enum;

enum TaskFilters: string {
    case Status = 'status';
    case PriorityFrom = 'priorityFrom';
    case PriorityTo = 'priorityTo';
    case Title = 'title';
    case SortBy = 'sortBy';
    case SortOrder = 'sortOrder';
}

