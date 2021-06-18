<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Responder\TokenResponder;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTGuard;

class LoginAction extends Controller
{
    private $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Responder\TokenResponder $responder
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, TokenResponder $responder): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = $this->authManager->guard('jwt');
        $token = $guard->attempt([
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ]);

        return $responder(
            $token,
            $guard->factory()->getTTL() * 60
        );
    }
}
