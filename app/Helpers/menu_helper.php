<?php

if (! function_exists('canViewMenu')) {

    function canViewMenu(?string $permission): bool
    {
        if ($permission === null) {
            return true;
        }

        $permissions = session('permissions') ?? [];

        return in_array($permission, $permissions);
    }
}
