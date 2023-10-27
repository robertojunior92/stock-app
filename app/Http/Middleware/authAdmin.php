<?php

namespace App\Http\Middleware;

use Closure;

class authAdmin
{
    /**return redirect()->route('login-admin');
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->session()->get('id');

        if ($id) {
            return $next($request);
        } else {
            $routeUri = str_replace("admin/", "", $request->route()->uri());

            if ($routeUri !== "login" && $routeUri !== "login-keycloak") {

                return redirect()->route('login-admin');
            } else {
                return $next($request);
            }
        }
    }
}
