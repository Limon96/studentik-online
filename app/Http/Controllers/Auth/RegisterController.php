<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'customer_group_id' => ['required', 'int', 'exists:customer_group,customer_group_id']
        ], [
            'email.unique' => 'Данный E-Mail уже зарегистрирован!',
            'password.required' => 'В пароле должно быть от 4 до 20 символов!',
            'password.string' => 'В пароле должно быть от 4 до 20 символов!',
            'password.min' => 'В пароле должно быть от 4 до 20 символов!',
            'customer_group_id.required' => 'Выберите свою роль автора или заказчика!',
            'customer_group_id.int' => 'Выберите свою роль автора или заказчика!',
            'customer_group_id.exists' => 'Выберите свою роль автора или заказчика!',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        $login = $this->generateLogin($user->id);
        $user->setLogin($login);

        Customer::create([
            'customer_id' => $user->id,
            'customer_group_id' => $request->customer_group_id,
            'customer_status_id' => 1,
            'customer_gender_id' => 0,
            'store_id' => 0,
            'language_id' => 1,
            'login' => $login,
            'firstname' => '',
            'bdate' => '0000-00-00',
            'country_id' => '176',
            'email' => $user->email,
            'telephone' => '',
            'gender' => '0',
            'image' => '',
            'languages' => '',
            'comment' => '',
            'ip' => $request->getClientIp(),
            'timezone' => '',
            'status' => '',
            'last_seen' => '',
            'safe' => '',
            'setting_email_notify' => '1',
            'setting_email_new_order' => '1',
        ]);

        return response()->json([
            'success' => "Успешно зарегистрирован",
            'redirect' => '../index.php?route=account/success',
        ]);
    }

    private function generateLogin(int $id)
    {
        return 'user' . $id;
    }
}
