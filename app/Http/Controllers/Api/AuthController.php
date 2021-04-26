<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    use ApiResponseTrait;

    public function userLogin(LoginRequest $request): JsonResponse
    {
        if ($request->validator->fails()) {
            return $this->apiResponse(null, $request->validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::guard('web')->attempt($credentials)) {
            if (Auth::guard('admin')->attempt($credentials)) {
                return $this->apiResponse(null, 'The administrator is not allowed to create event .. login as Organizer', Response::HTTP_UNAUTHORIZED);
            } else {
                return $this->apiResponse(null, 'invalid email or password', Response::HTTP_UNAUTHORIZED);
            }
        }
        $user = Auth::guard('web')->user();
        $data = $this->generateToken($user, 'user');
        return $this->apiResponse($data, 'Successfully Logged in', Response::HTTP_OK);
    }

    public function adminLogin(LoginRequest $request): JsonResponse
    {
        if ($request->validator->fails()) {
            return $this->apiResponse(null, $request->validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = request(['email', 'password']);
        if (!Auth::guard('admin')->attempt($credentials)) {
            if (Auth::guard('web')->attempt($credentials)) {
                return $this->apiResponse(null, 'You do not have privileges to access administrator dashboard', Response::HTTP_UNAUTHORIZED);
            } else {
                return $this->apiResponse(null, 'invalid email or password', Response::HTTP_UNAUTHORIZED);
            }
        }
        $user = Auth::guard('admin')->user();
        $data = $this->generateToken($user, 'admin');
        return $this->apiResponse($data, 'Successfully Logged in', Response::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return $this->apiResponse(null, 'Successfully logged out', Response::HTTP_OK);
    }

    public function generateToken($user, $scope, $remember_me = false): array
    {
        $tokenResult = $user->createToken('Personal Access Token', [$scope]);
        return array_merge(json_decode((new UserResource($user))->toJson(), true), [
            'token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ]);
    }


}
