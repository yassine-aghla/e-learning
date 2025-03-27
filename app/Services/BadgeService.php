<?php
namespace App\Services;

use App\Models\User;
use App\Models\Badge;

class BadgeService
{
    public function checkMentorBadges(User $user)
    {
        $badges = Badge::where('type', 'mentor')->get();
        
        foreach ($badges as $badge) {
            if ($this->meetsMentorConditions($user, $badge->conditions)) {
                $this->awardBadge($user, $badge);
            }
        }
    }

    public function checkStudentBadges(User $user)
    {
        $badges = Badge::where('type', 'student')->get();
        
        foreach ($badges as $badge) {
            if ($this->meetsStudentConditions($user, $badge->conditions)) {
                $this->awardBadge($user, $badge);
            }
        }
    }

    protected function meetsMentorConditions(User $user, array $conditions)
    {
        
        return $user->courses()->count() >= ($conditions['min_courses'] ?? 0)
            && $user->students()->count() >= ($conditions['min_students'] ?? 0)
            && $user->created_at->diffInMonths(now()) >= ($conditions['min_months_active'] ?? 0);
    }

    protected function meetsStudentConditions(User $user, array $conditions)
    {
        
        return $user->completedCourses()->count() >= ($conditions['min_completed_courses'] ?? 0)
            && $user->enrolledCourses()->count() >= ($conditions['min_enrolled_courses'] ?? 0)
            && $user->created_at->diffInMonths(now()) >= ($conditions['min_months_active'] ?? 0)
            && $user->profile_completed;
    }

    protected function awardBadge(User $user, Badge $badge)
    {
        if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
            $user->badges()->attach($badge->id, ['earned_at' => now()]);
        }
    }
}