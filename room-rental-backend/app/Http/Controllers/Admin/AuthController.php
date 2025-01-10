<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Hash;

use App\Constants\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\LoginRequest;
use App\Http\Requests\Admin\Admin\LogoutRequest;
use App\Http\Requests\Admin\Admin\RefreshTokenRequest;
use App\Http\Requests\Admin\Admin\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{
    public function __construct(protected readonly AuthService $authService, protected readonly UserService $userService) {}

    /**
     * @throws \Exception
     */
    public function create(RegisterRequest $request): Response
    {
        $result = DB::transaction(function () use ($request) {
            return $this->authService->register(User::class, $request->only('email', 'password'));
        });

        return $this->respond($result);
    }

    public function login(LoginRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $data = $request->only('email', 'password');

        if (empty($data['email']) || empty($data['password'])) {
            return response()->json([
                'error' => 'Email và mật khẩu là bắt buộc.'
            ], 400);
        }

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng.'
            ], 401);
        }

        return response()->json([
            'message' => 'Đăng nhập thành công.'
        ]);
    }

    public function logout(LogoutRequest $request): Response
    {
        $user = auth()->user();
        $this->authService->revokeToken($user->token());

        return $this->respond();
    }

    public function refreshToken(RefreshTokenRequest $request)
    {
        $data = $request->only('refresh_token');

        $result = $this->authService->refreshToken(Admin::class, $data['refresh_token']);
        if (!$result) {
            throw new UnauthorizedHttpException('', __('api.exception.invalid_refresh_token'));
        }

        return $this->respond($result);
    }

    public function profile()
    {
        $user = auth()->user();

        return $this->respond(['user' => $user]);
    }

    public function activeAccount($token)
    {
        if (strlen($token) !== Common::REQUEST_ACCOUNT_TOKEN_LENGTH) {
            return $this->respondWithError(Response::HTTP_BAD_REQUEST, Response::HTTP_BAD_REQUEST, "Your link you provided is not valid");
        }
        $validateInfo = $this->authService->checkValidToken($token);
        if (!$validateInfo['is_valid']) {
            return $this->respondWithError(Response::HTTP_BAD_REQUEST, Response::HTTP_BAD_REQUEST, '', $validateInfo);
        }

        return $this->authService->activeUser($validateInfo['data']->user_id, $validateInfo['data']->id);
    }

    public function index()
    {
        return $this->respond($this->userService->paginate());
    }

    public function show($id)
    {
        return $this->respond($this->userService->show($id));
    }

    public function update($id, Request $request)
    {
        return $this->respond($this->userService->update(intval($id), $request->only('name', 'email', 'avt', 'phone', 'address', 'is_active')));
    }

    public function destroy($id)
    {
        return $this->respond($this->userService->destroy(intval($id, true)));
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }

    public function changePassword($id, Request $request)
    {
        $user = User::find($id);
        if(!Hash::check($request->old_password, $user->password)){
            return response()->json([
                'message' => 'Old password is incorrect'
            ], 400);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        return response()->json([
            'message' => 'Password changed successfully'
        ], 200);
    }
}
