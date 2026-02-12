<?php

namespace App\Controllers\API;

use App\Controllers\BaseController;
use Firebase\JWT\JWT;
use Config\JWT as JWTConfig;

class AuthController extends BaseController
{
    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->db->table('users')
            ->where('email', $email)
            ->where('is_active', 1)
            ->get()
            ->getRow();

        if (! $user || ! password_verify($password, $user->password)) {
            return $this->failUnauthorized('Invalid credentials');
        }

        // Ambil permission
        $permissions = $this->getUserPermissions($user->id);

        $jwtConfig = new JWTConfig();

        $payload = [
            'iss' => $jwtConfig->issuer,
            'iat' => time(),
            'exp' => time() + $jwtConfig->expire,
            'sub' => $user->id,
            'company_id' => $user->company_id,
            'branch_id'  => $user->branch_id,
            'permissions'=> $permissions
        ];

        $token = JWT::encode($payload, $jwtConfig->key, $jwtConfig->algo);
        $refreshToken = bin2hex(random_bytes(40));

        $this->db->table('refresh_tokens')->insert([
            'user_id'    => $user->id,
            'token'      => $refreshToken,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+14 days')),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->respond([
            'token' => $token,
            'access_token'  => $token,
            'refresh_token' => $refreshToken,
            'expires_in'    => $jwtConfig->expire,
            'user'          => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'company_id' => $user->company_id,
                'branch_id' => $user->branch_id
            ],
            'permissions' => $permissions
        ]);
    }

    private function getUserPermissions(int $userId): array
    {
        $rows = $this->db->table('user_roles ur')
            ->select("CONCAT(p.module,'.',p.action) as permission")
            ->join('roles r', 'r.id = ur.role_id')
            ->join('role_permissions rp', 'rp.role_id = r.id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('ur.user_id', $userId)
            ->groupBy('permission')
            ->get()
            ->getResultArray();

        return array_column($rows, 'permission');
    }

    public function refresh()
    {
        $refreshToken = $this->request->getPost('refresh_token');

        if (! $refreshToken) {
            return $this->failUnauthorized('Refresh token required');
        }

        $tokenRow = $this->db->table('refresh_tokens')
            ->where('token', $refreshToken)
            ->where('revoked_at', null)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->get()
            ->getRow();

        if (! $tokenRow) {
            return $this->failUnauthorized('Invalid refresh token');
        }

        $user = $this->db->table('users')
            ->where('id', $tokenRow->user_id)
            ->where('is_active', 1)
            ->get()
            ->getRow();

        if (! $user) {
            return $this->failUnauthorized();
        }

        // Ambil permission (boleh cache ulang)
        $permissions = $this->getUserPermissions($user->id);

        // Generate access token baru
        $jwtConfig = new \Config\JWT();

        $payload = [
            'iss' => $jwtConfig->issuer,
            'iat' => time(),
            'exp' => time() + $jwtConfig->expire,
            'sub' => $user->id,
            'company_id' => $user->company_id,
            'branch_id'  => $user->branch_id,
            'permissions'=> $permissions
        ];

        $accessToken = \Firebase\JWT\JWT::encode(
            $payload,
            $jwtConfig->key,
            $jwtConfig->algo
        );

        // Rotate refresh token
        $newRefreshToken = bin2hex(random_bytes(40));

        $this->db->table('refresh_tokens')
            ->where('id', $tokenRow->id)
            ->update([
                'revoked_at' => date('Y-m-d H:i:s')
            ]);

        $this->db->table('refresh_tokens')->insert([
            'user_id' => $user->id,
            'token' => $newRefreshToken,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+14 days')),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->respond([
            'access_token'  => $accessToken,
            'refresh_token' => $newRefreshToken,
            'expires_in'    => $jwtConfig->expire
        ]);
    }

    public function logout()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        $refreshToken = $this->request->getPost('refresh_token');

        // Blacklist access token
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $accessToken = str_replace('Bearer ', '', $authHeader);

            $this->db->table('token_blacklists')->insert([
                'token_hash' => hash('sha256', $accessToken),
                'expires_at' => date('Y-m-d H:i:s', time() + 3600),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Revoke refresh token
        if ($refreshToken) {
            $this->db->table('refresh_tokens')
                ->where('token', $refreshToken)
                ->update([
                    'revoked_at' => date('Y-m-d H:i:s')
                ]);
        }

        return $this->respond([
            'message' => 'Logged out successfully'
        ]);
    }

    public function logoutAll()
    {
        $user = service('request')->user;

        // revoke semua refresh token
        $this->db->table('refresh_tokens')
            ->where('user_id', $user->sub)
            ->update([
                'revoked_at' => date('Y-m-d H:i:s')
            ]);

        return $this->respond([
            'message' => 'Logged out from all devices'
        ]);
    }
}
