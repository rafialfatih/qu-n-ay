<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetUserPasswordRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class ResetUserPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ResetUserPasswordRequest $request, User $user): RedirectResponse
    {
        $user->update([
            'password' => bcrypt($request->new_password),
        ]);

        return redirect('questions');
    }
}
