<?php

namespace App\Http\Controllers\Api;

use App\Application\CreateUserUseCase;
use App\Application\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Factory;
use App\Application\GetUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResources;
use App\Application\UpdateUserUseCase;
use Illuminate\Validation\ValidationException;
use App\Application\Requests\UpdateUserRequest;
use Throwable;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::all();

        return UserResources::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Factory $validationFactory
     * @param CreateUserUseCase $createUserUseCase
     * @return JsonResponse
     */
    public function store(Request $request, Factory $validationFactory, CreateUserUseCase $createUserUseCase): JsonResponse
    {
        try {
            $validationFactory->make($request->all(), [
                'name' => [
                    'string',
                    'required',
                ],
                'surnames' => [
                    'string',
                    'required',
                ],
                'email' => [
                    'string',
                    'required',
                ],
                'password' => [
                    'string',
                    'required',
                ],
                'repeatPassword' => [
                    'string',
                    'required',
                ],
                'dateOfBirth' => [
                    'string',
                    'required',
                ],
            ])->validate();

            $userRequest = new CreateUserRequest();
            $userRequest->name = $request->get('name');
            $userRequest->surname = $request->get('surnames');
            $userRequest->email = $request->get('email');
            $userRequest->password = $request->get('password');
            $userRequest->repeatPassword = $request->get('repeatPassword');
            $userRequest->dateOfBirth = $request->get('dateOfBirth');

            $resource = $createUserUseCase->execute($userRequest);

            $response = new JsonResponse($resource, Response::HTTP_CREATED);

        } catch (Throwable $ex) {
            $response = new JsonResponse($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Factory $validationFactory
     * @param GetUserUseCase $getUserUseCase
     * @return JsonResponse
     */
    public function show(Request $request, Factory $validationFactory, GetUserUseCase $getUserUseCase): JsonResponse
    {
        try{
            $routeParameters = $request->route() !== null ? $request->route()->parameters() : [];
            $validationFactory->make($routeParameters, [
                'userId' => [
                    'numeric',
                    'required'
                ]
            ])->validate();

            $id = $request->route('userId');

            $resource = $getUserUseCase->execute($id);

            $response = new JsonResponse($resource, Response::HTTP_OK);

        } catch (Throwable $ex) {
            $response = new JsonResponse($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Factory $validationFactory
     * @param UpdateUserUseCase $updateUserUseCase
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(
        Request $request,
        Factory $validationFactory,
        UpdateUserUseCase $updateUserUseCase
    ): JsonResponse {
        try {
            $routeParameters = $request->route() !== null ? $request->route()->parameters() : [];
            $validationFactory->make($routeParameters, [
                'userId' => [
                    'numeric',
                    'required'
                ]
            ])->validate();

            $validationFactory->make($request->all(), [
                'name' => [
                    'string',
                    'sometimes',
                ],
                'surnames' => [
                    'string',
                    'sometimes',
                ],
                'password' => [
                    'string',
                    'sometimes',
                ],
                'repeatPassword' => [
                    'string',
                    'sometimes',
                ],
                'dateOfBirth' => [
                    'string',
                    'sometimes',
                ],
            ])->validate();

            $userRequest = new UpdateUserRequest();
            $userRequest->userId = $request->route('userId');
            $userRequest->name = $request->get('name');
            $userRequest->surname = $request->get('surnames');
            $userRequest->password = $request->get('password');
            $userRequest->repeatPassword = $request->get('repeatPassword');
            $userRequest->dateOfBirth = $request->get('dateOfBirth');

            $resource = $updateUserUseCase->execute($userRequest);

            $response = new JsonResponse($resource, Response::HTTP_OK);
        } catch (Throwable $ex) {
            $response = new JsonResponse($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $userId = $request->route('userId');

            $user = User::find($userId);

            $user->delete();

            $response = new JsonResponse($user->userId());
        } catch (Throwable $ex) {
            $response = new JsonResponse($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $response;
    }
}
