<?php

namespace App\Enum;

enum TaskStatus: int {
    case Todo = 1;
    case Done = 2;
    case Reject = 3;
}

