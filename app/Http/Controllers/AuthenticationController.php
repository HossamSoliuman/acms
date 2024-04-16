<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateAuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $validData = $request->validated();
        $validData['password'] = Hash::make($validData['password']);
        if (isset($validData['is_eng'])) {
            $validData['role'] = 'eng';
        }
        $user = User::create($validData);
        $token = $user->createToken('auth_token')->plainTextToken;
        $user = User::find($user->id);
        return $this->apiResponse(
            [
                'token' => $token,
                'user' => UserResource::make($user),
            ],
        );
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return $this->apiResponse(null, 'Email or password is wrong', 0, 401);
        }

        $user = User::where('email', $validated['email'])->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->apiResponse(
            [
                'token' => $token,
                'user' => UserResource::make($user),
            ],
        );
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->tokens()->delete();
            return $this->apiResponse([], 'Successfully logged out');
        } else {
            return $this->apiResponse([], 'Unauthorized', 0, 401);
        }
    }

    public function update(UpdateAuthRequest $request)
    {
        $user=User::find($request->user()->id);

        $validated = $request->validated();

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return $this->apiResponse(
            [
                'user' => UserResource::make($user),
            ],
            'User updated successfully'
        );
    }
    public function user(Request $request)
    {
        return $this->apiResponse(UserResource::make($request->user()));
    }
}
