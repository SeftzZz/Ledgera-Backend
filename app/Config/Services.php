<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Services\WsEmitter;
use App\Services\PermissionService;

class Services extends BaseService
{
    public static function wsEmitter(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('wsEmitter');
        }

        return new WsEmitter();
    }

    public static function permission(bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('permission');
        }

        return new PermissionService();
    }
}
