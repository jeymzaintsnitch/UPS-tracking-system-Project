<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsAudit
{
    /**
     * Records an action in the audit_logs table.
     */
    protected function logAction(string $action, string $entity, int $entityId, ?array $oldValues = null, ?array $newValues = null)
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'entity'     => $entity,
            'entity_id'  => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}
