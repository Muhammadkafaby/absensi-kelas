<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Check if user has required role
     *
     * @param RequestInterface $request
     * @param array|null       $arguments - array of allowed roles
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $userRole = session()->get('role');

        // If no role specified in arguments, allow any logged-in user
        if (empty($arguments)) {
            return;
        }

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $arguments)) {
            session()->setFlashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            return redirect()->to('/dashboard');
        }
    }

    /**
     * After method - not used
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
