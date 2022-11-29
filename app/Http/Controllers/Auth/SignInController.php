<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerAuth;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SignInController extends Controller
{

    use RedirectsUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.sign_in');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (!$this->attemptLogin($request)) {
            if ($this->attemptCustomerLogin($request)) {
                $this->setNewPassword($this->credentialsByLogin($request));
            }
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout()
    {
        Auth::logout();

        return response()->redirectTo(
            config('APP_URL') . 'index.php?route=account/auth/log_out'
        );
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->guard()->attempt(
            $this->credentials($request)
        );

        $access_token = $this->createAccessToken(
            $this->credentialsByLogin($request)
        );

        return response()
            ->json([
                'success' => 1,
                'redirect' => config('APP_URL') . 'index.php?route=account/auth&access_token=' . $access_token
            ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json([
            'error' => 1,
            'error_warning' => 'Неверный логин или пароль',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::attempt(
            $this->credentials($request)
        );
    }

    public function createAccessToken(array $credentials)
    {
        $access_token = $this->generateAccessToken();

        $customer = $this->getCustomer($credentials);

        $customerAuth = CustomerAuth::create([
            'user_id' => $customer->customer_id,
            'access_token' => $access_token,
            'expired_at' => $this->expiredAt()
        ]);

        return $access_token;
    }

    protected function user(Request $request)
    {
        if ($this->isEmailCredentials($request)) {
            return User
                ::where('email', $request->get('login'))
                ->where('password', Hash::make($request->get('password')))
                ->first();
        }

        return User
            ::where('login', $request->get('login'))
            ->where('password', Hash::make($request->get('password')))
            ->first();
    }

    protected function credentials(Request $request)
    {
        if ($this->isEmailCredentials($request)) {
            return $this->credentialsByEmail($request);
        }

        return $this->credentialsByLogin($request);
    }

    protected function isEmailCredentials(Request $request)
    {
        $credentials = $request->only('login');

        return filter_var($credentials['login'], FILTER_VALIDATE_EMAIL);
    }

    protected function credentialsByEmail(Request $request)
    {
        $credentials = $request->only('login', 'password');

        return [
            'email' => $credentials['login'],
            'password' => $credentials['password'],
        ];
    }

    protected function credentialsByLogin(Request $request)
    {
        return $request->only('login', 'password');
    }

    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:6'
        ]);
    }

    protected function attemptCustomerLogin(Request $request)
    {
        return $this->existsCustomer(
            $this->credentialsByLogin($request)
        );
    }

    protected function existsCustomer(array $credentials)
    {
        $customer = Customer
            ::where(function ($query) use ($credentials) {
                return $query
                    ->where('login', $credentials['login'])
                    ->orWhere('email', $credentials['login']);
            })
            ->where('password', DB::raw("SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . DB::raw($credentials['password']) . "')))))"))
            ->exists();

        return $customer;
    }

    protected function getCustomer(array $credentials)
    {
        $customer = Customer
            ::where(function ($query) use ($credentials) {
                return $query
                    ->where('login', $credentials['login'])
                    ->orWhere('email', $credentials['login']);
            })
            ->where('password', DB::raw("SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . DB::raw($credentials['password']) . "')))))"))
            ->select(['customer_id', 'email', 'login'])
            ->first();

        return $customer;
    }

    protected function setNewPassword($credentials)
    {
        $login = $credentials['login'];
        $password = $credentials['password'];

        return User
            ::where(function ($query) use ($login) {
                return $query
                    ->where('login', $login)
                    ->orWhere('email', $login);
            })
            ->first()
            ->update([
                'password' => Hash::make($password)
            ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    protected function generateAccessToken()
    {
        return sha1(sha1(microtime(true) . md5(microtime(true) / rand(2, 9))));
    }

    protected function expiredAt()
    {
        return now()->addMinute();
    }
}
