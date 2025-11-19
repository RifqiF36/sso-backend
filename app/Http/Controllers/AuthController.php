<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SsoIdentity;
use App\Models\SsoProvider;
use App\Services\UserContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        /** @var User|null $user */
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials.'],
            ])->status(401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->buildUserProfile($user),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();
        return response()->json(['message' => 'logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($this->buildUserProfile($request->user()));
    }

    public function roles(Request $request)
    {
        $request->validate(['module' => 'nullable|string']);

        $roles = UserContextService::roles(
            $request->user()->id,
            $request->query('module')
        )->all();

        return response()->json(['roles' => $roles]);
    }

    public function refresh()
    {
        return response()->json(['message' => 'Use /auth/login to get a new token']);
    }

    /**
     * ============================================================
     * SSO AS IDENTITY PROVIDER (IdP) - untuk aplikasi seperti SIPRIMA
     * ============================================================
     */

    /**
     * Step 1: Authorize - aplikasi client (SIPRIMA) request user ke authorize
     */
    public function ssoAuthorize(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'redirect_uri' => 'required|url',
            'state' => 'required|string',
            'response_type' => 'nullable|string',
        ]);

        // Get authenticated user
        $user = auth('sanctum')->user();
        if (!$user) {
            return response()->json(['message' => 'User must be authenticated'], 401);
        }

        $clientId = $request->query('client_id');
        $redirectUri = $request->query('redirect_uri');
        $state = $request->query('state');

        // Validasi client_id
        if ($clientId !== 'siprima-app') {
            return response()->json(['message' => 'Invalid client_id'], 400);
        }

        // Validasi redirect_uri
        $allowedRedirects = explode(',', env('SSO_ALLOWED_REDIRECTS'));
        if (!in_array($redirectUri, $allowedRedirects)) {
            return response()->json(['message' => 'Invalid redirect_uri'], 400);
        }

        // Generate authorization code
        $authCode = bin2hex(random_bytes(32));
        Cache::put("sso_auth_code:{$authCode}", [
            'user_id' => $user->id,
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'state' => $state,
            'created_at' => now(),
        ], now()->addMinutes(5));

        // Kembalikan code ke client
        return response()->json([
            'data' => [
                'auth_code' => $authCode,
                'state' => $state,
                'redirect_uri' => $redirectUri,
            ]
        ]);
    }

    /**
     * Step 2: Token - aplikasi client (SIPRIMA) tukar code dengan access token
     */
    public function ssoToken(Request $request)
    {
        $request->validate([
            'grant_type' => 'required|string',
            'code' => 'required_if:grant_type,authorization_code|string',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'redirect_uri' => 'required|url',
        ]);

        $grantType = $request->input('grant_type');
        $clientId = $request->input('client_id');
        $clientSecret = $request->input('client_secret');
        $redirectUri = $request->input('redirect_uri');
        $code = $request->input('code');

        // Validasi client credentials
        $validSecret = env('CLIENT_SIPRIMA_SECRET');
        if ($clientId !== 'siprima-app' || $clientSecret !== $validSecret) {
            return response()->json(['message' => 'Invalid client credentials'], 401);
        }

        if ($grantType !== 'authorization_code') {
            return response()->json(['message' => 'Unsupported grant type'], 400);
        }

        // Cek authorization code
        $authData = Cache::pull("sso_auth_code:{$code}", null);
        if (!$authData) {
            return response()->json(['message' => 'Invalid or expired code'], 400);
        }

        if ($authData['client_id'] !== $clientId || $authData['redirect_uri'] !== $redirectUri) {
            return response()->json(['message' => 'Code mismatch'], 400);
        }

        // Get user from auth code
        $user = User::find($authData['user_id']);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 401);
        }

        // Generate access token untuk SIPRIMA
        $user->tokens()->delete();
        $accessToken = $user->createToken('sso-siprima')->plainTextToken;

        return response()->json([
            'data' => [
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'expires_in' => env('SSO_JWT_EXPIRE', 7200),
            ]
        ]);
    }

    /**
     * Step 3: UserInfo - aplikasi client (SIPRIMA) get info user dengan access token
     */
    public function ssoUserinfo(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'id' => $user->id,
            'username' => $user->email,
            'email' => $user->email,
            'name' => $user->name,
            'roles' => UserContextService::roles($user->id)->pluck('role')->all(),
        ]);
    }

    /**
     * ============================================================
     * SSO AS CLIENT - SSO Mitra bisa juga login ke external IdP
     * ============================================================
     */
    public function ssoRedirect(Request $request)
    {
        $provider = SsoProvider::query()->where('enabled', true)->firstOrFail();
        $state = bin2hex(random_bytes(16));
        Cache::put("sso_state:{$state}", ['provider' => $provider->provider_id], now()->addMinutes(10));

        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $provider->client_id,
            'redirect_uri' => $provider->redirect_uri,
            'scope' => implode(' ', $provider->scopes ?? []),
            'state' => $state,
        ]);

        return response()->json(['url' => "{$provider->authorize_url}?{$query}"]);
    }

    public function ssoCallback(Request $request)
    {
        $code = $request->query('code');
        $state = $request->query('state');
        $cached = $state ? Cache::pull("sso_state:{$state}", null) : null;

        if (!$code || !$cached) {
            return response()->json(['message' => 'Invalid SSO response'], 400);
        }

        $provider = SsoProvider::query()->where('provider_id', $cached['provider'])->firstOrFail();

        $tokenResponse = Http::asForm()->post($provider->token_url, [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $provider->redirect_uri,
            'client_id' => $provider->client_id,
            'client_secret' => $provider->client_secret,
        ])->json();

        if (!isset($tokenResponse['access_token'])) {
            return response()->json(['message' => 'Failed to exchange code'], 401);
        }

        $userInfo = [];
        if ($provider->userinfo_url) {
            $userInfo = Http::withToken($tokenResponse['access_token'])
                ->get($provider->userinfo_url)
                ->json() ?? [];
        }

        $email = $userInfo['email'] ?? null;
        $sub = $userInfo['sub'] ?? ($userInfo['id'] ?? null);
        if (!$email && !$sub) {
            return response()->json(['message' => 'SSO userinfo missing'], 400);
        }

        $user = User::firstOrCreate(
            ['email' => $email ?: "{$sub}@sso.local"],
            ['name' => $userInfo['name'] ?? 'SSO User', 'password' => bcrypt(str()->random(16))]
        );

        SsoIdentity::updateOrCreate(
            ['provider_id' => $provider->provider_id, 'provider_subject' => $sub ?: $email],
            ['user_id' => $user->id, 'raw' => $userInfo]
        );

        $user->tokens()->delete();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $this->buildUserProfile($user),
            'provider' => $provider->provider_id,
        ]);
    }

    private function buildUserProfile(User $user): array
    {
        $roles = UserContextService::roles($user->id)->all();
        $tenants = UserContextService::tenants($user->id)->all();

        $apps = collect(config('role_apps', []))
            ->filter(function ($appConfig) use ($roles) {
                $allowedRoles = collect($appConfig['roles'] ?? []);
                if ($allowedRoles->isEmpty() || $allowedRoles->contains('*')) {
                    return true;
                }

                return $allowedRoles->intersect(array_column($roles, 'role'))->isNotEmpty();
            })
            ->map(function ($appConfig, $code) {
                return [
                    'code' => $code,
                    'name' => $appConfig['name'] ?? ucfirst($code),
                    'url' => $appConfig['url'] ?? '#',
                    'icon' => $appConfig['icon'] ?? null,
                ];
            })
            ->values()
            ->all();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'tenants' => $tenants,
            'apps' => $apps,
        ];
    }
}

