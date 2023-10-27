<?php

namespace App\Http\Controllers;

use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use App\Services\AdminService;
use App\Support\Controllers\AdminBaseController;
use Laravel\Socialite\Facades\Socialite;

class AdminController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    private $adminService;

    public function __construct()
    {
        $this->adminService = new AdminService();
    }
    public function index()
    {
        if (session('id')) {
            return redirect()->route('products');
        }

        return view("login-admin")->with(["error" => false]);
    }

    public function clearCache(Request $request)
    {
        $key = $request->input("key");

        $value = Cache::pull($key);

        return response()->json($value);
    }

    public function getHashid($databaseInput)
    {
        $encryptKey = config('app.app_salt');

        $hashids = new Hashids($encryptKey);
        return $hashids->encode($databaseInput);
    }

    public function createHash($databaseInput)
    {
        return response($this->getHashid($databaseInput));
    }

    public function login(Request $request, AdminService $adminService)
    {
        $email = strval($request->input('email'));
        $password = strval($request->input('password'));

        $result = $adminService->login($email, $password);

        if ($result) {
            return redirect()->route('dash');

        } else {
            return view('login-admin')->with(["error" => true]);
        }
    }

    /**
     * Trata o callback do Keycloak iniciando a sessão pelo Id do usuário
     * @param AdminService $adminService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginKeycloak(AdminService $adminService) {
        $user = Socialite::driver('keycloak')->user();
        if ($user && isset($user->user['userId'])) {
            session(['keycloakUser' => $user]);
            $logged = $adminService->loginByUserId($user->user['userId']);
            if ($logged) {
                $route = $this->getPermission();
                return redirect()->route($route);
            }
        }
        throw new \Exception('Failed do authenticate user');
    }

    /**
     * Redireciona para a URL de logout do keycloak
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    private function logoutKeycloak() {
        try {
            $user = session('keycloakUser');
            session()->flush();
            if ($user && isset($user->accessTokenResponseBody['id_token'])) {
                $idToken = $user->accessTokenResponseBody['id_token'];

                $logoutUrl = config('services.keycloak.base_url') . '/realms/'
                    . config('services.keycloak.realms') . '/protocol/openid-connect/logout?'
                    . '&id_token_hint=' . $idToken
                    . '&post_logout_redirect_uri=' . config('services.keycloak.redirect_logout');
                return redirect($logoutUrl);
            }
        } catch (\Throwable $ex) {
        }
        return redirect()->route('login-admin');
    }

    public function logout()
    {
        // se existe configuração para o Keycloak, também faz o logout no keycloak
        if (env('LOGIN_MODE') === 'keycloak' && env('KEYCLOAK_URL')) {
            return $this->logoutKeycloak();
        }

        session()->flush();
        return redirect()->route('login-admin');
    }

    public function getLicense(Request $request, AdminService $adminService)
    {
        $email = $request->input('email');

        $result = $adminService->getLicense($email);

        return response()->json($result);
    }

    public function getClinic(Request $request, AdminService $adminService)
    {
        $licenseID = $request->input("licenseID");

        $result = $adminService->getClinic($licenseID);

        return response()->json($result);
    }

    public function requestsIndex()
    {
        return view("publicapi::stats-requests");
    }

    public function getRequests()
    {
        $repository = new Repository();
        $res = $repository->getRequests();

        return response()->json($res);
    }

    public function getLogs($fileName = false)
    {
        if ($fileName) {
            return response()->json(file_get_contents(storage_path("logs/{$fileName}")));
        } else {
            return response()->json(array_diff(scandir(storage_path("logs")), array('.', '..')));
        }
    }

    public function redirectToLoginAdmin() {
        return redirect()->route('login-admin');
    }
}

