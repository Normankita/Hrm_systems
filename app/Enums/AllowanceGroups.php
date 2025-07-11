<?php


namespace App\Enums;


enum AllowanceGroups: string
{
    case INDIVIDUAL = 'individual';

    case GROUP = 'group';

    case CATEGORY = 'category';
}
