<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

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

    public function login(Request $request)
    {
        $this->validateLogin($request);
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where('email', '=', $request->email)
            ->whereIn('user_role', [array_flip(Role::ROLES)['Admin']])
            ->first();

        if (!empty($user)) {
            if (Hash::check($request->password, $user->password)) {
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
            } else {
                return redirect()->route('login')
                    ->withErrors(['password' => 'Incorrect password.'])
                    ->withInput($request->only('email'));
            }
        } else {
            return redirect()->route('login')
                ->withErrors(['email' => 'The provided email does not exist.'])
                ->withInput($request->only('email'));
        }
    }
}
