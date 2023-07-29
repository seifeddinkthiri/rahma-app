<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Account;
use App\Models\User;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Auth/register');
    }
    public function store(LoginRequest $request)
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['required'],
        ]);

        $User = new User();

        $User->account_id = '1';
        $User->first_name = $request->first_name;
        $User->last_name = $request->last_name;
        $User->email = $request->email;
        $User->password = $request->password;
        $User->email_verified_at = now();
        $User->remember_token = Str::random(10);

        $User->save();
        return redirect()->route('login')->with('success','تمت إضافة المستخدم');
    }
}
