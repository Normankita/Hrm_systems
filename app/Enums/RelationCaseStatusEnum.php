<?php

namespace App\Enums;

enum RelationCaseStatusEnum: string
{
    case OPEN = 'Open';
    case INVESTIGATING = 'Investigating';
    case RESOLVED = 'Resolved';
    case CLOSED = 'Closed';
}
