<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Freelancer = 'freelancer';
    case Client = 'client';
}
