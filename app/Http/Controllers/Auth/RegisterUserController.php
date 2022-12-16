<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisterUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(RegisterUserRequest $request)
    {
        $user = $request->safe()->merge([
            'password' => bcrypt($request->password),
        ]);

        $register = User::create($user->all());

        Auth::login($register);

        return redirect('/')->with('message', 'You are registered!');
    }
}
