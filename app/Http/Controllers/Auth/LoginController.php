<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Auth;
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
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $this->validate($request, [
            'username' => 'required', 'password' => 'required',
        ]);
        
        $username = $request->username;
        $pass = $request->password;

        if (Auth::attempt(['username' => $username, 'password' => $pass])) {
            $buffer = Auth::user();
            if($buffer->status==1){
                return response()->json(array('status' => 1, 'message' => trans('auth.success')));
            }else{
                Auth::logout();
                \Session::flush();
                return response()->json(array('status' => 0, 'message' => trans('auth.user_allready_login')));
            }
        }else {
            return response()->json(array('status' => 0, 'message' => trans('auth.failed')));
        }
    }
}
