<?php

namespace App\Enums;

enum TrainingParticipantStatusEnum: string
{
    case ENROLLED = 'Enrolled';
    case IN_PROGRESS = 'In Progress';
    case COMPLETED = 'Completed';
    case WITHDRAWN = 'Withdrawn';
}
