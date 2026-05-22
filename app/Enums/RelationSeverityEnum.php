<?php

namespace App\Enums;

enum RelationSeverityEnum: string
{
    case LOW = 'Low';
    case MEDIUM = 'Medium';
    case HIGH = 'High';
    case CRITICAL = 'Critical';
}
