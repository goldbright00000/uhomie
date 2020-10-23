<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\User;

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

    use AuthenticatesUsers {
        logout as performLogout;
        login as performLogin;
    }

    

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->username = $this->findUsername();
    }
    public function username()
    {
        return $this->username;
    }
    public function findUsername()
    {
        $login = request()->input('login');
 
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
 
        request()->merge([$fieldType => $login]);
        
        return $fieldType;
    }
    public function login(Request $request)
    {
        //$this->validateLogin($request);
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            //'g-recaptcha-response'=>'required|recaptcha',
        ]);

        if ($this->attemptLogin($request)) {
            $user = $this->guard()->user();
            $user->generateToken();
            
            return $this->performLogin($request);
        }
        
        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return $this->performLogout($request);
    }
    public function loginSuperusuario(Request $request, $email)
    {
        if(env('SUPERUSER_LOGIN', 0) == 1){
            $user = User::where('email', $email)->first();
            if($user)
            {
                Auth::login($user);
                Auth::guard()->login($user);
                return redirect()->route('start');
            }else{
                abort(401, 'usuario no existe');
            }
        }else{
            abort(401, 'metodo deshabilitado');
        }
        
    }
    
}
