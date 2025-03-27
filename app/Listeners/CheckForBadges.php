<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CourseCompleted;
use App\Services\BadgeService;

class CheckForBadges
{
    public function handle(CourseCompleted $event)
    {
        $badgeService = new BadgeService();
        
        if ($event->user->hasRole('student')) {
            $badgeService->checkStudentBadges($event->user);
        } elseif ($event->user->hasRole('mentor')) {
            $badgeService->checkMentorBadges($event->user);
        }
    }
}