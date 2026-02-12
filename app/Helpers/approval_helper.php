<?php

if (! function_exists('hasPendingApproval')) {

    function hasPendingApproval(
        int $referenceId,
        string $module,
        ?int $branchId = null
    ): bool
    {
        $builder = db_connect()
            ->table('approval_requests')
            ->where([
                'reference_id' => $referenceId,
                'module'       => $module,
                'status'       => 'pending'
            ])
            ->where('is_active', 1);

        if ($branchId !== null) {
            $builder->where('branch_id', $branchId);
        }

        return $builder->countAllResults() > 0;
    }
}

if (! function_exists('isFullyApproved')) {

    function isFullyApproved(int $referenceId, string $module): bool
    {
        return ! hasPendingApproval($referenceId, $module);
    }
}

if (! function_exists('canApprove')) {

    function canApprove(
        int $referenceId,
        string $module,
        int $userId
    ): bool
    {
        return db_connect()
            ->table('approval_requests ar')
            ->join('user_roles ur', 'ur.role_id = ar.role_id')
            ->where([
                'ar.reference_id' => $referenceId,
                'ar.module'       => $module,
                'ar.status'       => 'pending',
                'ur.user_id'      => $userId
            ])
            ->countAllResults() > 0;
    }
}

