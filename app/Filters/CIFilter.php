<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Libraries\CIAuth;

class CIFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */

    public function before(RequestInterface $request, $arguments = null)
    {
        if ($arguments[0] == 'guestVoter') {
            if ( CIAuth::checkVoter() ) {
                return redirect()->route('user.home');
            } elseif ( CIAuth::check() ) {
                return redirect()->route('admin.home');
            }
        }

        if ($arguments[0] == 'guestAdmin') {
            if ( CIAuth::check() ) {
                return redirect()->route('admin.home');
            } elseif ( CIAuth::checkVoter() ) {
                return redirect()->route('user.home');
            }
        }

        if ($arguments[0] == 'authVoter') {
            if ( !CIAuth::checkVoter() ) {
                return redirect()->route('user.login.form')->with('fail', 'Debes identificarte primero!');
            }
        }

        if ($arguments[0] == 'authAdmin') {
            if ( !CIAuth::check() ) {
                return redirect()->route('admin.login.form')->with('fail', 'Debes iniciar sesión primero!');
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
