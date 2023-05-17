<?php

namespace App\Dto;

final class TaskDto
{
    public ?string $title;
    public ?string $description;
    public ?int $status;
    public ?int $priority;
}