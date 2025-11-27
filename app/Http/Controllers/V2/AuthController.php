<?php

namespace App\Http\Controllers\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Otp;
use App\Mail\VerificationOtp;
use App\Mail\ResetPasswordOtp;
use App\Services\MenuService;
use OpenApi\Annotations as OA; // <<< DITAMBAHKAN AGAR ANOTASI DIKENALI

/**
 * @OA\Tag(
 * name="Auth V2",
 * description="Authentication V2 - Simplified authentication with OTP verification, password reset, and role-based menu"
 * )
 */
class AuthController extends Controller
{
    /**
     * Get authenticated user profile
     * * @OA\Get(
     * path="/api/v2/auth/me",
     * operationId="AuthV2Me",
     * tags={"Auth V2"},
     * summary="Get authenticated user profile with menu",
     * security={{"BearerAuth":{}}},
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="user", type="object"),
     * @OA\Property(property="menu", type="array", @OA\Items(type="object"))
     * )
     * )
     * )
     * )
     */
    public function me()
    {
        $user = Auth::user();
        
        // Get menu based on user role
        $menu = MenuService::getMenuByRole($user->role);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'menu' => $menu
            ]
        ]);
    }

    /**
     * Get user by ID
        *
        * @OA\Get(
        * path="/api/v2/auth/user/{id}",
        * operationId="AuthV2GetUserById",
        * tags={"Auth V2"},
        * summary="Get user by ID",
        * security={{"BearerAuth":{}}},
        * @OA\Parameter(
        * name="id",
        * in="path",
        * required=true,
        * description="User ID",
        * @OA\Schema(type="integer", example=1)
        * ),
        * @OA\Response(
        * response=200,
        * description="Success",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="data", type="object")
        * )
        * ),
        * @OA\Response(response=404, description="User not found")
        * )
     */
    public function byId($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Login user
        *
        * @OA\Post(
        * path="/api/v2/auth/login",
        * operationId="AuthV2Login",
        * tags={"Auth V2"},
        * summary="Login user",
        * description="Authenticate user with email and password.",
        * @OA\RequestBody(
        * required=true,
        * @OA\JsonContent(
        * required={"email","password"},
        * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
        * @OA\Property(property="password", type="string", format="password", example="Password123")
        * )
        * ),
        * @OA\Response(
        * response=200,
        * description="Login successful",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="Login successful"),
        * @OA\Property(property="data", type="object")
        * )
        * ),
        * @OA\Response(response=401, description="Invalid credentials"),
        * @OA\Response(response=403, description="Email not verified")
        * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Check if email is verified
        if (!$user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email not verified'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    /**
     * Register new user
        *
        * @OA\Post(
        * path="/api/v2/auth/register",
        * operationId="AuthV2Register",
        * tags={"Auth V2"},
        * summary="Register new user account",
        * description="Create a new user account and send OTP verification email",
        * @OA\RequestBody(
        * required=true,
        * @OA\JsonContent(
        * required={"name","email","password","password_confirmation"},
        * @OA\Property(property="name", type="string", example="John Doe"),
        * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
        * @OA\Property(property="password", type="string", format="password", minLength=8, example="Password123"),
        * @OA\Property(property="password_confirmation", type="string", format="password", example="Password123"),
        * @OA\Property(property="nip", type="string", nullable=true, example="199001012020011001"),
        * @OA\Property(property="phone", type="string", nullable=true, example="081234567890")
        * )
        * ),
        * @OA\Response(
        * response=201,
        * description="Registration successful",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="Registration successful. Please check your email for verification code."),
        * @OA\Property(property="data", type="object")
        * )
        * ),
        * @OA\Response(response=422, description="Validation error")
        * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'nip' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nip' => $request->nip,
            'phone' => $request->phone,
        ]);

        // Generate OTP for email verification
        $otpCode = rand(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new VerificationOtp($otpCode, $user->name));

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please check your email for verification code.',
            'data' => [
                'user' => $user
            ]
        ], 201);
    }

    /**
     * Verify email with OTP
        *
        * @OA\Post(
        * path="/api/v2/auth/verify",
        * operationId="AuthV2Verify",
        * tags={"Auth V2"},
        * summary="Verify email with OTP",
        * description="Verify user email address using OTP code sent during registration",
        * @OA\RequestBody(
        * required=true,
        * @OA\JsonContent(
        * required={"email","otp"},
        * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
        * @OA\Property(property="otp", type="string", example="123456")
        * )
        * ),
        * @OA\Response(
        * response=200,
        * description="Email verified successfully",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="Email verified successfully"),
        * @OA\Property(property="data", type="object")
        * )
        * ),
        * @OA\Response(response=400, description="Invalid or expired OTP"),
        * @OA\Response(response=404, description="User not found")
        * )
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $otp = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->where('verified_at', null)
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Mark OTP as verified
        $otp->verified_at = now();
        $otp->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'data' => [
                'token' => $token,
                'user' => $user
            ]
        ]);
    }

    /**
     * Logout user
        *
        * @OA\Post(
        * path="/api/v2/auth/logout",
        * operationId="AuthV2Logout",
        * tags={"Auth V2"},
        * summary="Logout user",
        * description="Revoke all user tokens and logout",
        * security={{"BearerAuth":{}}},
        * @OA\Response(
        * response=200,
        * description="Logged out successfully",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="Logged out successfully")
        * )
        * ),
        * @OA\Response(response=401, description="Unauthenticated")
        * )
     */
    public function logout()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Send password reset OTP
        *
        * @OA\Post(
        * path="/api/v2/auth/reset-password",
        * operationId="AuthV2ResetPassword",
        * tags={"Auth V2"},
        * summary="Request password reset",
        * description="Send OTP code to user email for password reset",
        * @OA\RequestBody(
        * required=true,
        * @OA\JsonContent(
        * required={"email"},
        * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com")
        * )
        * ),
        * @OA\Response(
        * response=200,
        * description="OTP sent successfully",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="Password reset code has been sent to your email")
        * )
        * ),
        * @OA\Response(response=404, description="User not found")
        * )
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Generate OTP for password reset
        $otpCode = rand(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'otp' => $otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new ResetPasswordOtp($otpCode, $user->name));

        return response()->json([
            'success' => true,
            'message' => 'Password reset code has been sent to your email'
        ]);
    }

    /**
     * Confirm password reset with OTP
        *
        * @OA\Post(
        * path="/api/v2/auth/confirm-reset-password",
        * operationId="AuthV2ConfirmResetPassword",
        * tags={"Auth V2"},
        * summary="Confirm password reset with OTP",
        * description="Reset user password using OTP code and new password",
        * @OA\RequestBody(
        * required=true,
        * @OA\JsonContent(
        * required={"email","otp","password","password_confirmation"},
        * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
        * @OA\Property(property="otp", type="string", example="123456"),
        * @OA\Property(property="password", type="string", format="password", minLength=8, example="NewPassword123"),
        * @OA\Property(property="password_confirmation", type="string", format="password", example="NewPassword123")
        * )
        * ),
        * @OA\Response(
        * response=200,
        * description="Password reset successfully",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="Password reset successfully")
        * )
        * ),
        * @OA\Response(response=400, description="Invalid or expired OTP"),
        * @OA\Response(response=404, description="User not found")
        * )
     */
    public function confirmResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->where('verified_at', null)
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $user = User::wheare('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Mark OTP as verified
        $otp->verified_at = now();
        $otp->save();

        // Revoke all tokens
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }
}