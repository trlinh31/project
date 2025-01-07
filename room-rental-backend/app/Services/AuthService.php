<?php

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Constants\Common;
use App\Mail\ActiveAccount;
use App\Models\User;
use App\Models\UserRequest;
use App\Utils\CommonUtil;
use Carbon\Carbon;
use Exception;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Passport\Client as OClient;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\Exceptions\AuthenticationException;
use Laravel\Passport\Token;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthService
{
    protected $oClient;
    protected $guards;

    public function __construct()
    {
        $this->_retrieveClients();
    }

    private function _retrieveClients(): void
    {
        $clients = OClient::query()->where('password_client', 1)->get();
        $configProviders = config('auth.providers');
        $configGuards = config('auth.guards');

        $providerGuardMaps = [];
        foreach ($configGuards as $guard => $configGuard) {
            if (isset($configGuard['provider']) && $configGuard['driver'] === 'session') {
                $providerGuardMaps[$configGuard['provider']] = $guard;
            }
        }

        foreach ($clients as $client) {
            if (isset($client['provider']) && isset($configProviders[$client['provider']])) {
                $model = $configProviders[$client['provider']]['model'];
                $this->oClient[$model] = $client;
                $this->guards[$model] = $providerGuardMaps[$client['provider']];
            }
        }
    }

    private function _getClient(string $key)
    {
        return $this->oClient[$key] ?? null;
    }

    private function _getGuard(string $key)
    {
        return $this->guards[$key] ?? null;
    }

    /**
     * @throws Exception
     */


    public function register(string $modelNamespace, $data)
    {

        $data['password'] = Hash::make($data['password']);
        $existingUser = $modelNamespace::where('email', $data['email'])->first();
        if ($existingUser) {
            return ['message' => 'Email already exists'];
        }

        $user = $modelNamespace::create($data);
        $token = $user->createToken('YourAppName')->accessToken;

        // $this->sendVerificationEmail($user);
        try {
            UserRequest::create([
                'user_id' => $user->id,
                'type' => Common::USER_REQUEST['ACTIVE_ACCOUNT'],
                'expired_at' => Carbon::now()->addDay(),
                'token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('UserRequest creation failed: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return [
            'user' => $user,
            'token' => $token
        ];
    }


    /**
     * @throws Exception
     */
    public function login(string $modelNamespace, $email, $password): array
    {

        $user = User::query()->where('email', $email)->first();

        if ($user && !$user->is_active) {
            throw new UnauthorizedException(__('auth.account_not_active'));
        }

        if (!$user) {
            throw new NotFoundHttpException(__('auth.user_not_found'));
        }


        if (!Hash::check($password, $user->password)) {
            throw new AuthenticationException(__('auth.invalid_credentials'));
        }

        $result = [];
        $tokenResult = $user->createToken('YourAppName');
        $result['token'] = $tokenResult->accessToken;


        if (!$result['token']) {
            throw new AuthenticationException();
        }

        $guard = auth($this->_getGuard($modelNamespace));
        if ($guard && $guard->attempt(compact('email', 'password'))) {
            $result['user'] = $guard->user();
        }
        return $result;
    }




    public function logout($user)
    {
        return $this->revokeToken($user->token());
    }

    public function profile(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return auth()->user();
    }


    /**
     * @param string $modelNamespace
     * @param string $refreshToken
     * @return mixed|null
     * @throws Exception
     */
    public function refreshToken(string $modelNamespace, string $refreshToken)
    {
        $oClient = $this->_getClient($modelNamespace);
        if (!$oClient) return null;

        $request = Request::create('/oauth/token', 'POST', [
            'grant_type' => 'refresh_token',
            'client_id' => (string)$oClient->id,
            'client_secret' => $oClient->secret,
            'refresh_token' => $refreshToken,
            'scope' => '*',
        ]);
        $response = app()->handle($request);
        if ($response->getStatusCode() === HttpResponse::HTTP_OK) {
            return json_decode((string)$response->getContent(), true);
        }
        return null;
    }

    /**
     * @param Token $token
     * @return mixed
     */
    public function revokeToken(Token $token)
    {
        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);

        $tokenRepository->revokeAccessToken($token->id);
        return $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
    }

    /**
     * @param Authenticatable $user
     * @param bool $include
     */
    public function revokeAllTokens(Authenticatable $user, bool $include = true)
    {
        $tokens = $user->tokens;
        $currentToken = $user->token();
        if (count($tokens)) {
            foreach ($tokens as $token) {
                if ($token === $currentToken && !$include) continue;
                $this->revokeToken($token);
            }
        }
    }

    public function checkValidToken($token)
    {
        $accountRequestInfo = UserRequest::query()
            ->where('token', $token)
            ->first();
        if (!$accountRequestInfo) {
            return [
                'is_valid' => false,
                'code' => Common::ACCOUNT_REQUEST_STATUS['INVALID_TOKEN']
            ];
        }

        if ($accountRequestInfo->type !== Common::USER_REQUEST['ACTIVE_ACCOUNT']) {
            return [
                'is_valid' => false,
                'code' => Common::ACCOUNT_REQUEST_STATUS['NOT_MATCH_TYPE']
            ];
        }

        $user = Auth::user();
        if ($user && $user->id !== $accountRequestInfo->user_id) {
            return [
                'is_valid' => false,
                'code' => Common::ACCOUNT_REQUEST_STATUS['NOT_MATCH_USER']
            ];
        }

        if ($accountRequestInfo->expired_at < Carbon::now()) {
            return [
                'is_valid' => false,
                'code' => Common::ACCOUNT_REQUEST_STATUS['TOKEN_EXPIRED']
            ];
        }

        return [
            'is_valid' => true,
            'code' => Common::ACCOUNT_REQUEST_STATUS['VALID'],
            'data' => $accountRequestInfo
        ];
    }

    public function activeUser($userId, $requestId)
    {
        $user = User::query()->find($userId);
        if (!$user) throw new NotFoundHttpException('User not found');
        $user->is_active = true;
        $user->save();

        UserRequest::destroy($requestId);
        return true;
    }
}
