<?php

namespace App\Services\Auth;

use App\Interfaces\Auth\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthService implements AuthServiceInterface
{
    public function login($request):User|null
    {
        if (!Auth::attempt($request)) {
            return null; // Authentication failed
        }
        return Auth::user();
    }


    public function register($request):User
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'employee',
            'password' => bcrypt($request->password),
        ];
        return User::create($data);
    }

    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->tokens()->delete(); // Revoke all tokens
        }
        return true;
    }


    public function refreshToken(): string
    {
        $user = Auth::user();
        if ($user) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return $token;
        }
        return 'null'; // No user authenticated
    }

    public function getUser(): User|null
    {
        return Auth::user();
    }

}
