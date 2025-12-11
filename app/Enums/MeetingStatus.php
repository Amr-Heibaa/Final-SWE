<?php

namespace App\Enums;

enum MeetingStatus: string
{
    case Pending   = 'pending';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
