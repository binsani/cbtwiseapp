<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminLogger
{
    /**
     * Log an administrative activity.
     */
    public static function log(
        string $action,
        $subject = null,
        array $meta = [],
        ?array $oldValues = null,
        ?array $newValues = null
    ): ?AdminActivityLog {
        $adminId = Auth::id();
        if (!$adminId) {
            return null;
        }

        $subjectType = null;
        $subjectId = null;

        if (is_object($subject)) {
            $subjectType = get_class($subject);
            $subjectId = $subject->id ?? null;
        } elseif (is_string($subject)) {
            $subjectType = $subject;
        }

        return AdminActivityLog::record(
            adminId: $adminId,
            action: $action,
            subjectType: $subjectType,
            subjectId: $subjectId,
            meta: $meta,
            oldValues: $oldValues,
            newValues: $newValues
        );
    }
}
