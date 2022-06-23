<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends Controller
{
    public function authCheck()
    {
        return Auth::guard('admin')->check() ?
            redirect()->route('admin.dashboard')
            : redirect()->route('admin.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => 'required|email',
            'password' => 'required'
        ]);
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/admin/dashboard');
        } else {
            return back()->withInput($request->only('email', 'remember'))->withErrors([['credential' => 'invalid credential']]);
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors($validator);
    }

    public function loginPage()
    {
        return view('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->to('/admin/login');
    }
}
