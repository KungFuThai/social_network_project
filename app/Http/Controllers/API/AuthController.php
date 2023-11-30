<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Traits\ImageStorageTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;
    use ImageStorageTrait;
    public function getAuth(){
        return $this->successResponse(auth()->user());
    }
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email'    => $request->get('email'),
            'password' => $request->get('password'),
        ];
        if ( ! Auth::attempt($credentials)) {
            return $this->errorResponse('Sai tên tài khoản hoặc mật khẩu');
        }

        $user = User::query()->where('email', $credentials['email'])->first();

        $token = $user->createToken("access_token")->plainTextToken;

        return $this->successResponse($token, 'Đăng nhập thành công!');
    }

    public function register(RegisterRequest $request)
    {
        $path = null;
        if($request->has('avatar')){
            $path = $this->storeImage('avatars', $request->file('avatar'));
        }
        $userAttributes = $request->validated();
        $userAttributes['avatar'] = $path;
        $userAttributes['password'] = Hash::make($request->get('password'));

        $user = User::query()->create($userAttributes);

        $token = $user->createToken('access_token')->plainTextToken;

        return $this->successResponse($token, 'Đăng ký tài khoản thành công!');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(message: 'Đăng xuất thành công!');
    }
}
