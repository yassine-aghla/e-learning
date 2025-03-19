<?php
namespace App\Repositories;

use App\Models\User;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function login(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return null;
        }
        return $token;
    }

    public function logout()
    {
        Auth::logout();
    }

    public function refresh()
    {
        return Auth::refresh();
    }

    public function user()
    {
        return Auth::user();
    }

    public function update(array $data)
    {
        
        $user = Auth::user();

    
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (isset($data['profile_picture'])) {
            $path = $data['profile_picture']->store('profile', 'public');
            $user->profile_picture = $path ;
            }
       
        if (isset($data['skills'])) {
            $user->skills = $data['skills'];
        }

        if (isset($data['bio'])) {
            $user->bio = $data['bio'];
        }

        $user->save();

        return $user;
    }

    
    public function deleteUser($id)
    {
        
        $user = User::findOrFail($id);
        $user->delete();
        return true;
    }
}