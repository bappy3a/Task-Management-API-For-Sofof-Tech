<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Auth\AuthServiceInterface;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    public function __construct(private AuthServiceInterface $service){}

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $data = $this->service->login($credentials);

        if(!$data){
            $message = 'Oops! That email or password doesn’t match our records.';
            return $this->ResponseError($message,null,$message,401);
        }
        return $this->loginSuccess($data);
    }

    function register(RegisterRequest $request)
    {
        try {
            $data = $this->service->register($request);
            return $this->loginSuccess($data);
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Process Error! Consult Tech Team');
        }
    }
    
    public function logout(Request $request): JsonResponse
    {
        try {
            $data = $this->service->logout();
            return $this->ResponseSuccess($data);
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Process Error! Consult Tech Team');
        }
    }
    public function user(Request $request): JsonResponse
    {
        try {
            $data = $this->service->getUser();
            return $this->ResponseSuccess($data);
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Process Error! Consult Tech Team');
        }
    }

    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $tokenResult = $this->service->refreshToken();
            $data['access_token'] = $tokenResult;
            $data['token_type'] = 'Bearer';
            return $this->ResponseSuccess($data);
        } catch (\Exception $e) {
            return $this->ResponseError($e->getMessage(). " in " . $e->getFile() . " on line " . $e->getLine(), null, 'Data Process Error! Consult Tech Team');
        }
    }

    protected function loginSuccess($user):JsonResponse
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $tokenResult->accessToken->expires_at = Carbon::now()->addWeeks(1);
        $tokenResult->accessToken->save();

        $data['access_token'] = $tokenResult->plainTextToken;
        $data['token_type'] = 'Bearer';
        $data['expires_at'] = Carbon::parse($tokenResult->accessToken->expires_at)->toDateTimeString();
        $data['user'] = $user;
        return $this->ResponseSuccess($data);
    }
}
