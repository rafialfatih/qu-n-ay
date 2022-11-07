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
        $user = $request->validated();
        $user->password = bcrypt($user->password);

        $register = User::create($user);

        Auth::login($register);

        return redirect('/')->with('message', 'You are registered!');
    }
}
