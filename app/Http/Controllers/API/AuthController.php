<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Traits\ImageStorageTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\ForgetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    use ResponseTrait;
    use ImageStorageTrait;

    public function getAuth()
    {
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
        if ($request->has('avatar')) {
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

    public function fogetPassword(Request $request)
    {
        $email = $request->email;
        $check = User::query()->where('email', $email)->exists();
        if ($check){
            $forgetPassword = ForgetPassword::query()
                ->updateOrCreate(
                    ['email' => $email],
                    [
                        'email' => $email,
                        'token' => random_int(100000, 999999),
                    ]
                );

            Mail::send('mail', compact('forgetPassword'), function ($email) use ($forgetPassword) {
                $title = config('app.name') . ' ' . 'đổi mật khẩu';
                $email->subject(config('app.name') . ' ' . 'đổi mật khẩu');
                $email->to($forgetPassword->email);
            });

            return $this->successResponse($forgetPassword['token'],'Gửi mail thành công hãy kiểm tra email của bạn!');
        }
        return $this->errorResponse('Email không tồn tại!');
    }

    public function resetPassword(Request $request)
    {
        $token = $request->token;
        $email = ForgetPassword::query()
            ->where('token', $token)
            ->pluck('email')
            ->toArray();
        $password = $request->password;

        User::query()
            ->where('email', $email)
            ->update([
                'password' => Hash::make($password),
            ]);

        ForgetPassword::query()->where('token', $token)->delete();

        return $this->successResponse(message: 'Đặt lại mật khẩu thành công! Hãy đăng nhập lại!');
    }
}
