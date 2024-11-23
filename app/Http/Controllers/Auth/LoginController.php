<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Agregar validación de estado
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['estado' => 1]);
    }

    // Personalizar el campo de usuario (si es necesario)
    public function username()
    {
        return 'email';
    }

    // Agregar validación después del login
    protected function authenticated(Request $request, $user)
    {
        if (!$user->estado) {
            auth()->logout();
            return back()->with('error', 'Tu cuenta está desactivada.');
        }

        // Actualizar último login
        $user->ultimo_login = now();
        $user->save();

        return redirect()->intended($this->redirectTo);
    }
}
