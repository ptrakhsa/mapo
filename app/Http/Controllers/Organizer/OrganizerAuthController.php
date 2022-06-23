<?php

namespace App\Http\Controllers\Organizer;

use App\Models\Organizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrganizerAuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('guest:organizer')->except('logout');
    // }


    public function authCheck()
    {
        return Auth::guard('organizer')->check() ?
            redirect()->route('organizer.dashboard')
            : redirect()->route('organizer.login');
    }

    public function registerPage()
    {
        return view('organizer.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required|max:255',
            'email'   => 'required|email|unique:organizers',
            'password' => 'required|min:6',
            'address' => 'required|max:255',
        ]);

        Organizer::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'address' => $request['address'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->intended('organizer/login');
    }

    public function logout()
    {
        Auth::guard('organizer')->logout();
        return redirect()->to('/organizer/login');
    }

    public function loginPage()
    {
        return view('organizer.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('organizer')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/organizer/dashboard');
        } else {
            return back()->withInput($request->only('email', 'remember'))->withErrors([['credential' => 'invalid credential']]);
        }

        return back()->withInput($request->only('email', 'remember'))->withErrors($validator);
    }
}
