<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];
        if(!Auth::attempt($credentials)){
            return response()->json([
                'success' => false,
                'message' => 'Sai tên tài khoản hoặc mật khẩu!',
                'data' => [],
            ], 401);
        }

        $user = User::query()->where('email',  $credentials['email'])->first();

        $token = $user->createToken("access_token")->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công!',
            'token' => $token
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = User::query()->create([
                'last_name'  => $request->get('last_name'),
                'first_name' => $request->get('first_name'),
                'phone' => $request->get('phone'),
                'email'      => $request->get('email'),
                'password'   => Hash::make($request->get('password')),
            ]);

            $token = $user->createToken('access_token')->plainTextToken;

            $success = true;
            $message = 'Đăng ký tài khoản thành công';
        } catch (QueryException $e) {
            $success = false;
            $message = $e->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
            'token' => $token,
        ];

        return response()->json($response);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            $success = true;
            $message = 'Đăng xuất thành công';
        }catch (QueryException $e){
            $success = false;
            $message = $e->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message,
        ];

        return response()->json($response);
    }
}
