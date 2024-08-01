<?php

namespace App\Enums;
enum StatusEnum: string
{
    case available = 'available';
    case pending = 'pending';
    case sold = 'sold';
}