<?php

namespace App\Enums;

enum EmployeeState {
    const SUSPENDED = 'suspended';
    const ACTIVE = 'active';
    const RETIRED = 'retired';
    const TERMINATED = 'terminated';
    const LEAVE = 'leave';
    const OTHER = 'other';
}
