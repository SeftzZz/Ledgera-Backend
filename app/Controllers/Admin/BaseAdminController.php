<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BaseAdminController extends BaseController
{
    protected $session;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->session = session();

        // contoh proteksi tambahan
        if (!$this->session->get('isLoggedIn')) {
            redirect()->to('/login')->send();
            exit;
        }
    }
}
