<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request)
    {
        $user = $this->authRepository->register($request->all());
        return response()->json(['user' => $user], 201);
        
    }

    public function login(Request $request)
    {
        $token = $this->authRepository->login($request->only('email', 'password'));
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        $this->authRepository->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken($this->authRepository->refresh());
    }

    public function user()
    {
        return response()->json($this->authRepository->user());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function update(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . auth()->id(),
            'password' => 'sometimes|string|min:8',
            'profile_picture' => 'sometimes',
            'bio' => 'sometimes|string',
            'skills' => 'sometimes|string',
        ]);

       
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $this->authRepository->update($request->all());

       
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function destroy($id)
    {
        $this->authRepository->deleteUser($id);
        return response()->json(['message' => 'User deleted successfully']);
    }
}