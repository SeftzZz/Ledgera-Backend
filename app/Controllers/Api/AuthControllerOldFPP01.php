<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RefreshTokenModel;
use App\Libraries\JwtService;
use Google\Client as GoogleClient;

class AuthController extends BaseController
{
    protected $user;
    protected $jwtService;
    protected $refreshModel;

    public function __construct()
    {
        $this->user = new UserModel();
        $this->jwtService = new JwtService();
        $this->refreshModel = new RefreshTokenModel();
    }

    private function issueToken(array $user)
    {
        $accessToken  = $this->jwtService->generateAccessToken((object) $user);
        $refreshToken = $this->jwtService->generateRefreshToken();

        $this->refreshModel->insert([
            'user_id'    => $user['id'],
            'token'      => $refreshToken,
            'expires_at' => date(
                'Y-m-d H:i:s',
                time() + config('JWT')->refreshTokenTTL
            ),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'access_token'  => $accessToken,
            'refresh_token' => $refreshToken,
            'expires_in'    => config('JWT')->accessTokenTTL,
            'user'          => $user
        ]);
    }

    public function register()
    {
        $data = $this->request->getJSON(true);

        $this->user->insert([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        return $this->response->setJSON(['message' => 'Register success']);
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $user = $this->user->where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Invalid login']);
        }

        return $this->issueToken($user);
    }

    public function google()
    {
        $json = $this->request->getJSON(true);

        if (!isset($json['token'])) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Token required']);
        }

        $client = new GoogleClient([
            'client_id' => env('GOOGLE_CLIENT_ID')
        ]);

        $payload = $client->verifyIdToken($json['token']);

        if (!$payload) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Invalid Google token']);
        }

        $user = $this->user->where('email', $payload['email'])->first();

        if (!$user) {
            $id = $this->user->insert([
                'name'        => $payload['name'] ?? null,
                'email'       => $payload['email'],
                'photo'       => $payload['picture'] ?? null,
                'provider'    => 'google',
                'provider_id' => $payload['sub'],
                'is_verified' => 1
            ]);

            $user = $this->user->find($id);
        }

        return $this->issueToken($user);
    }

    public function facebook()
    {
        $json = $this->request->getJSON(true);

        if (!isset($json['access_token'])) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Access token required']);
        }

        $url = 'https://graph.facebook.com/me'
            . '?fields=id,name,email,picture.type(large)'
            . '&access_token=' . urlencode($json['access_token']);

        $response = file_get_contents($url);
        $fbUser = json_decode($response, true);

        if (!isset($fbUser['email'])) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Facebook email permission required']);
        }

        $user = $this->user->where('email', $fbUser['email'])->first();

        if (!$user) {
            $id = $this->user->insert([
                'name'        => $fbUser['name'] ?? null,
                'email'       => $fbUser['email'],
                'photo'       => $fbUser['picture']['data']['url'] ?? null,
                'provider'    => 'facebook',
                'provider_id' => $fbUser['id'],
                'is_verified' => 1
            ]);

            $user = $this->user->find($id);
        }

        return $this->issueToken($user);
    }

    public function refresh()
    {
        $json = $this->request->getJSON(true);
        $refreshToken = $json['refresh_token'] ?? null;

        if (!$refreshToken) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['message' => 'Refresh token required']);
        }

        $tokenData = $this->refreshModel
            ->where('token', $refreshToken)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->first();

        if (!$tokenData) {
            return $this->response
                ->setStatusCode(401)
                ->setJSON(['message' => 'Invalid refresh token']);
        }

        $user = $this->user->find($tokenData['user_id']);

        $newAccessToken = $this->jwtService->generateAccessToken((object) $user);

        return $this->response->setJSON([
            'access_token' => $newAccessToken,
            'expires_in'   => config('JWT')->accessTokenTTL
        ]);
    }
}
