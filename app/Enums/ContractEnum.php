<?php

namespace App\Enums;

enum ContractEnum: string
{
    case FIXED_TERM = 'Fixed';

    case PERMANENT = 'Permanent';

    case PROBATION = 'Probation';
}
