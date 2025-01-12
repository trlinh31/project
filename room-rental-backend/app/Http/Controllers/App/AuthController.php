<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\App\User\LoginRequest;
use App\Http\Requests\App\User\LogoutRequest;
use App\Http\Requests\App\User\RefreshTokenRequest;
use App\Http\Requests\App\User\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @throws \Exception
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->only('name', 'email', 'password', 'phone');

        $existingUser = User::where('email', $data['email'])->first();

        if ($existingUser) {
            return response()->json([
                'message' => 'Email đã tồn tại'
            ], 400);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone']
        ]);

        $token = $user->createToken('YourAppName')->accessToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->only('email', 'password');

        $user = User::query()->where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email không tồn tại'
            ], 404);
        }

        if($user && !$user->is_active) {
            return response()->json([
                'message' => 'Tài khoản chưa được kích hoạt'
            ], 400);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Sai mật khẩu'
            ], 400);
        }

        $token = $user->createToken('YourAppName')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(LogoutRequest $request)
    {
        $this->authService->logout(auth()->user());

        return $this->respond();
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $data = $request->only('refresh_token');

        $result = $this->authService->refreshToken(User::class, $data['refresh_token']);
        if (!$result) {
            throw new UnauthorizedHttpException('', __('api.exception.invalid_refresh_token'));
        }

        return $this->respond($result);
    }

    public function profile()
    {
        $user = $this->authService->profile();

        return $this->respond(['user' => $user]);
    }
}
