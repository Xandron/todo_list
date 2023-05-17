<?php

namespace App\Dto;

final class TaskFilterDto
{
    public ?string $status;
    public ?string $priorityFrom;
    public ?string $priorityTo;
    public ?string $title;
    public ?string $sortBy;
    public ?string $sortOrder;
}