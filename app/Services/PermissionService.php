<?php

namespace App\Services;

class PermissionService
{
    public function cache(int $userId): array
    {
        $cacheKey = 'permissions_user_' . $userId;

        if ($cached = cache($cacheKey)) {
            return $cached;
        }

        $db = db_connect();

        $rows = $db->table('permissions p')
            ->select('p.code')
            ->join('role_permissions rp', 'rp.permission_id = p.id')
            ->join('user_roles ur', 'ur.role_id = rp.role_id')
            ->where('ur.user_id', $userId)
            ->get()
            ->getResultArray();

        $permissions = array_column($rows, 'code');

        cache()->save($cacheKey, $permissions, 3600);

        return $permissions;
    }

}
