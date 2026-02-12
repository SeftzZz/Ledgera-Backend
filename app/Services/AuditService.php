<?php

namespace App\Services;

use App\Models\AuditLogModel;

class AuditService
{
    public static function log(
        int $companyId,
        string $table,
        int $recordId,
        string $action,
        array $oldData = null,
        array $newData = null,
        int $userId = null
    ) {
        (new AuditLogModel())->insert([
            'company_id' => $companyId,
            'table_name' => $table,
            'record_id'  => $recordId,
            'action'     => $action,
            'old_data'   => $oldData ? json_encode($oldData) : null,
            'new_data'   => $newData ? json_encode($newData) : null,
            'user_id'    => $userId
        ]);
    }
}
