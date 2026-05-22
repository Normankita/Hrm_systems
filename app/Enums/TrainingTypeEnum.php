<?php

namespace App\Enums;

enum TrainingTypeEnum: string
{
    case INTERNAL = 'Internal';
    case EXTERNAL = 'External';
    case ONLINE = 'Online';
    case WORKSHOP = 'Workshop';
}
