<?php

namespace App\Enums;

enum DisciplineActionEnum: string
{
    case VERBAL_WARNING = 'Verbal Warning';
    case WRITTEN_WARNING = 'Written Warning';
    case SUSPENSION = 'Suspension';
    case DEMOTION = 'Demotion';
    case TERMINATION = 'Termination';
    case OTHER = 'Other';
}
