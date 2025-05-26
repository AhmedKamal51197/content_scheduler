<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Register API (POST , formdata)
    public function register(RegisterRequest $request)
    {

        $data = $request->validated();
        try {
            $user=User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password']
            ]);
            $tokenResult = $user->createToken('auth_token');
            $token       = $tokenResult->accessToken;
            $token->expires_at = Carbon::now()->addMinute(30);
            $token->save();
            return $this->success(data:[
                'user' => $user,
                'token' => $tokenResult->plainTextToken
            ]);
        } catch (Exception $e) {
            return $this->failure('Error occures : '.$e->getMessage().' => please try again');
        }
    }
    // Login API (POST , formdata)
    public function login(LoginRequest $request) {
        $data = $request->validated();
        try {
            if (auth()->attempt($data)) {
                $user = auth()->user();
                $tokenResult = $user->createToken('auth_token');
                $token       = $tokenResult->accessToken;
                $token->expires_at = Carbon::now()->addMinute(30);
                $token->save();
                return $this->success(data:[
                    'user' => $user,
                    'token' => $tokenResult->plainTextToken
                ]);
            } else {
                return $this->failure('Invalid credentials');
            }
        } catch (Exception $e) {
            return $this->failure('Error occures : '.$e->getMessage().' => please try again');
        }
    }
    // Logout API (POST , formdata)
    public function logout() {}
    // get Profile API 
    public function profile() {
        try {
            $user = auth()->user();
            return $this->success(data: [
                'user' => $user
            ]);
        } catch (Exception $e) {
            return $this->failure('Error occures : '.$e->getMessage().' => please try again');
        }
    }
    // update profile API
    public function updateProfile(UpdateProfileRequest $request) {
        try {
            $user = auth()->user();
            $data = $request->validated();
            $user->update($data);
            return $this->success(data: [
                'user' => $user
            ],message:'Profile updated successfully');
        } catch (Exception $e) {
            return $this->failure('Error occures : '.$e->getMessage().' => please try again');
        }
    }
    // delete profile API
    public function deleteProfile() {
        try {
            $user = auth()->user();
            $user->delete();
            return $this->success(message:'Profile deleted successfully');
        } catch (Exception $e) {
            return $this->failure('Error occures : '.$e->getMessage().' => please try again');
        }
    }
}
