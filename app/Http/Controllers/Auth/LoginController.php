<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string', 'min:8'],
            ]);
            if ($validator->fails()) {
                return response()->json(array('status' => 'error','msg' => 'Failed Login','err'=>'Harap Periksa Kembali Inputan '.$validator->errors()), 200);
            }
            else{
                $user = User::firstWhere('email',$request->get('email'));
                if ($user && Hash::check($request->get('password'),$user->password)){
                    Auth::login($user);
                    return response()->json(array('status' => 'success','data'=>$user,'msg' => 'Login Success'), 200);
                }
                else{
                    return response()->json(array('status' => 'error','msg' => 'Username or Password Wrong'), 200);
                }
            }        
        } catch (\Throwable $e) {
            return response()->json(array('status' => 'error','msg' => 'Failed Login','err'=>$e->getMessage()), 500);
        }
    }

    
}
