<?php

if (! function_exists('hasPermission')) {

    function hasPermission(string $permission): bool
    {
        $permissions = session('permissions');

        if (empty($permissions) || ! is_array($permissions)) {
            return false;
        }

        return in_array($permission, $permissions, true);
    }
}
