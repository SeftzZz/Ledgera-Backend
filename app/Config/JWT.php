<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public string $key     = 'LEDGERA_SUPER_SECRET_KEY_123';
    public string $algo    = 'HS256';
    public int    $expire  = 60 * 60 * 6; // 6 jam
    public string $issuer = 'ledgera.app';
}
