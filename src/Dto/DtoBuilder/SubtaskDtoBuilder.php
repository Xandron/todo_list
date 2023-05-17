<?php

namespace App\Dto\DtoBuilder;

use App\Dto\SubtaskDto;
use App\Enum\SubtaskFields;

final class SubtaskDtoBuilder
{
    public static function build(array $data): SubtaskDto
    {
        $subtaskDto = new SubtaskDto();
        $subtaskDto->title = $data[SubtaskFields::Title->value] ?? null;
        $subtaskDto->description = $data[SubtaskFields::Description->value] ?? null;
        $subtaskDto->status = $data[SubtaskFields::Status->value] ?? null;

        return $subtaskDto;
    }
}