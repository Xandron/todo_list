<?php

namespace App\Enum;

enum TaskFields: string {
    case Title = 'title';
    case Description = 'description';
    case Status = 'status';
    case Priority = 'priority';
}

