<?php

namespace App\Enums;

enum TrainingStatusEnum: string
{
    case SCHEDULED = 'Scheduled';
    case ONGOING = 'Ongoing';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Cancelled';
}
