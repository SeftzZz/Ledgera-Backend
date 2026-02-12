<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;

class JWTAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');

        if (! $header || ! str_starts_with($header, 'Bearer ')) {
            return service('response')->setStatusCode(401)->setJSON([
                'message' => 'Token required'
            ]);
        }

        $token = str_replace('Bearer ', '', $header);
        $config = new JWTConfig();

        try {
            $decoded = JWT::decode($token, new Key($config->key, $config->algo));
            $request->user = $decoded; // inject user
        } catch (\Exception $e) {
            return service('response')->setStatusCode(401)->setJSON([
                'message' => 'Invalid token'
            ]);
        }

        $tokenHash = hash('sha256', $token);

		$isBlacklisted = db_connect()
		    ->table('token_blacklists')
		    ->where('token_hash', $tokenHash)
		    ->where('expires_at >=', date('Y-m-d H:i:s'))
		    ->countAllResults();

		if ($isBlacklisted > 0) {
		    return service('response')->setStatusCode(401)->setJSON([
		        'message' => 'Token revoked'
		    ]);
		}
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
