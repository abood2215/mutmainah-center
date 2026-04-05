<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ActivityLogger
{
    public static function log(string $action, string $subject, int $subjectId, string $description = ''): void
    {
        try {
            $user     = auth()->user();
            $userId   = $user?->getAuthIdentifier() ?? 0;
            $userName = $user?->getName() ?? '';

            DB::table('activity_logs')->insert([
                'user_id'     => $userId,
                'user_name'   => $userName,
                'action'      => $action,
                'subject'     => $subject,
                'subject_id'  => $subjectId,
                'description' => $description,
                'created_at'  => now(),
            ]);
        } catch (\Throwable) {
            // لا نوقف التطبيق إذا فشل التسجيل
        }
    }
}
