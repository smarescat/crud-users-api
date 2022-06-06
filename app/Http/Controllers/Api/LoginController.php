<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Application\Resources\AuthResponseResource;
use App\Utilities\Exceptions\UserNotFoundException;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $email = $request->get('email');
            $password = $request->get('password');

            $user = User::query()->where('email', '=', $email)->first();

            if ($user == null) {
                throw new UserNotFoundException('User not found');
            }

            if (hash('sha256', $password) != $user->password) {
                throw new UserNotFoundException('Password is incorrect');
            }

            $accessToken = $user->createToken('User Token')->accessToken;

            $response = new AuthResponseResource();
            $response->userId = $user->userId();
            $response->name = $user->name();
            $response->surname = $user->surname();
            $response->token = $accessToken;
        } catch (UserNotFoundException $ex) {
            return new JsonResponse($ex->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}
