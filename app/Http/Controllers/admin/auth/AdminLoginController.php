<?php

namespace App\Http\Controllers\admin\auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLogin;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session;
class AdminLoginController extends Controller
{
    public function dashborad(){
        return view('admin.dashboard');
    }
    public function adminLogin(){
        return view('admin.Auth.login');
    }
    public function checkLogin(AdminLogin $request){

        if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended('admin');
        }
        return back()->withInput($request->only('email', 'remember'))->with(['error'=>'هناك خطأ في البيانات']);
    }

}
