<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $secret;
    public string $algo = 'HS256';

    public int $accessTokenTTL  = 900;        // 15 menit
    public int $refreshTokenTTL = 2592000;    // 30 hari

    public function __construct()
    {
        $this->secret = env('JWT_SECRET');
    }
}
