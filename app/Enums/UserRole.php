<?php

namespace App\Enums;

enum UserRole: string
{
    case CONTRACTOR = 'CONTRACTOR';
    case ADMIN = 'ADMIN';
    case LGU_PG = 'LGU/PG';
    case UNVERIFIED = 'UNVERIFIED';
}
