<?php

namespace App\Interfaces\Auth;
use App\Interfaces\BaseInterface;

interface AuthServiceInterface
{
    public function login($request);
    public function register($request);
    public function logout();
    public function refreshToken();
    public function getUser();
}
