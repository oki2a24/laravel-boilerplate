<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisteredUserStoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * 受信した登録リクエストを処理します。
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisteredUserStoreRequest $request): Response
    {
        /** @var string $password */
        $password = $request->input('password');
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }
}
