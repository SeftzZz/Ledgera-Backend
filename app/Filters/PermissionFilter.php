<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! $arguments) {
            return;
        }

        $permission = $arguments[0];

        if (! hasPermission($permission)) {

            // API / AJAX request
            if (
                $request->isAJAX() ||
                str_starts_with($request->getUri()->getPath(), 'api')
            ) {
                return service('response')
                    ->setStatusCode(403)
                    ->setJSON([
                        'status'  => false,
                        'message' => 'Access denied'
                    ]);
            }

            // 🔹 WEB request
            return redirect()
                ->to('/dashboard')
                ->with('error', 'You do not have permission');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nothing
    }
}
