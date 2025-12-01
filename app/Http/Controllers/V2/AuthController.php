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

        $user = User::with(['role', 'dinas', 'unitkerja'])->find(Auth::id());

        // Ambil nama role (string) untuk mapping menu
        $roleName = $user->role ? $user->role->name : null;
        $menu = MenuService::getMenuByRole($roleName);

        // Tampilkan menu sesuai format lama (pakai array asli, bukan hanya nama)
        $menuList = $menu;

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'role' => $user->role ? $user->role->name : null,
                    'dinas' => $user->dinas ? $user->dinas->name : null,
                    'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deleted_at' => $user->deleted_at,
                ],
                'menu' => $menuList
            ]
        ]);
    }

    /**
     * Get all users
        *
        * @OA\Get(
        * path="/api/v2/auth/users",
        * operationId="AuthV2GetAllUsers",
        * tags={"Auth V2"},
        * summary="Get all users",
        * security={{"BearerAuth":{}}},
        * @OA\Response(
        * response=200,
        * description="Success",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="data", type="array", @OA\Items(type="object"))
        * )
        * )
        * )
     */
    public function getAllUsers()
    {
        $users = User::with(['role', 'dinas', 'unitkerja'])->get();

        $userData = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nip' => $user->nip,
                'jenis_kelamin' => $user->jenis_kelamin,
                'role' => $user->role ? $user->role->name : null,
                'dinas' => $user->dinas ? $user->dinas->name : null,
                'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => $user->deleted_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $userData
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
        $user = User::with(['role', 'dinas', 'unitkerja', ])->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'nip' => $user->nip,
                'jenis_kelamin' => $user->jenis_kelamin,
                'role' => $user->role ? $user->role->name : null,
                'dinas' => $user->dinas ? $user->dinas->name : null,
                'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => $user->deleted_at,
            ]
        ]);
    }

    /**
     * Update user data
        *
        * @OA\Put(
        * path="/api/v2/auth/user/{id}",
        * operationId="AuthV2UpdateUser",
        * tags={"Auth V2"},
        * summary="Update user data",
        * security={{"BearerAuth":{}}},
        * @OA\Parameter(
        * name="id",
        * in="path",
        * required=true,
        * description="User ID",
        * @OA\Schema(type="integer", example=1)
        * ),
        * @OA\RequestBody(
        * required=true,
        * @OA\JsonContent(
        * @OA\Property(property="name", type="string", example="John Doe Updated"),
        * @OA\Property(property="email", type="string", format="email", example="john.updated@example.com"),
        * @OA\Property(property="phone", type="string", example="081234567890"),
        * @OA\Property(property="nip", type="string", example="199001012020011001"),
        * @OA\Property(property="jenis_kelamin", type="string", enum={"laki-laki", "perempuan"}, example="laki-laki"),
        * @OA\Property(property="role_id", type="integer", example=1),
        * @OA\Property(property="dinas_id", type="integer", example=1),
        * @OA\Property(property="unit_kerja_id", type="integer", example=1)
        * )
        * ),
        * @OA\Response(
        * response=200,
        * description="User updated successfully",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="User updated successfully"),
        * @OA\Property(property="data", type="object")
        * )
        * ),
        * @OA\Response(response=404, description="User not found"),
        * @OA\Response(response=422, description="Validation error")
        * )
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'nip' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:laki-laki,perempuan',
            'role_id' => 'nullable|exists:roles,id',
            'dinas_id' => 'nullable|exists:dinas,id',
            'unit_kerja_id' => 'nullable|exists:unitkerjas,id',
        ]);

        // Update only provided fields
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }
        if ($request->has('nip')) {
            $user->nip = $request->nip;
        }
        if ($request->has('jenis_kelamin')) {
            $user->jenis_kelamin = $request->jenis_kelamin;
        }
        if ($request->has('role_id')) {
            $user->role_id = $request->role_id;
        }
        if ($request->has('dinas_id')) {
            $user->dinas_id = $request->dinas_id;
        }
        if ($request->has('unit_kerja_id')) {
            $user->unit_kerja_id = $request->unit_kerja_id;
        }

        $user->save();
        $user = User::with(['role', 'dinas', 'unitkerja'])->find($id);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'nip' => $user->nip,
                'jenis_kelamin' => $user->jenis_kelamin,
                'role' => $user->role ? $user->role->name : null,
                'dinas' => $user->dinas ? $user->dinas->name : null,
                'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'deleted_at' => $user->deleted_at,
            ]
        ]);
    }

    /**
     * Delete user (soft delete)
        *
        * @OA\Delete(
        * path="/api/v2/auth/user/{id}",
        * operationId="AuthV2DeleteUser",
        * tags={"Auth V2"},
        * summary="Delete user (soft delete)",
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
        * description="User deleted successfully",
        * @OA\JsonContent(
        * @OA\Property(property="success", type="boolean", example=true),
        * @OA\Property(property="message", type="string", example="User deleted successfully")
        * )
        * ),
        * @OA\Response(response=404, description="User not found")
        * )
     */
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Soft delete
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
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
        * @OA\Property(property="email", type="string", format="email", example="admin.kota@example.com"),
        * @OA\Property(property="password", type="string", format="password", example="password123")
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

        $user = User::with(['role', 'dinas', 'unitkerja'])->where('email', $request->email)->first();

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
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'role' => $user->role ? $user->role->name : null,
                    'dinas' => $user->dinas ? $user->dinas->name : null,
                    'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deleted_at' => $user->deleted_at,
                ]
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
        * required={"name","email","password","password_confirmation","jenis_kelamin","phone"},
        * @OA\Property(property="name", type="string", example="John Doe"),
        * @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
        * @OA\Property(property="password", type="string", format="password", minLength=8, example="Password123"),
        * @OA\Property(property="password_confirmation", type="string", format="password", example="Password123"),
        * @OA\Property(property="jenis_kelamin", type="string", enum={"laki-laki", "perempuan"}, example="laki-laki"),
        * @OA\Property(property="phone", type="string", example="081234567890"),
        * @OA\Property(property="nip", type="string", nullable=true, example="199001012020011001"),
        * @OA\Property(property="role_id", type="integer", nullable=true, description="Role ID (optional, default to staff role if not provided)", example=1),
        * @OA\Property(property="dinas_id", type="integer", nullable=true, description="Dinas ID (optional)", example=1),
        * @OA\Property(property="unit_kerja_id", type="integer", nullable=true, description="Unit Kerja ID (optional)", example=1)
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
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'phone' => 'required|string|max:20',
            'nip' => 'nullable|string|max:50',
            'role_id' => 'nullable|exists:roles,id',
            'dinas_id' => 'nullable|exists:dinas,id',
            'unit_kerja_id' => 'nullable|exists:unitkerjas,id',
        ]);

        // Jika role_id tidak diberikan, set default ke role 'staff'
        $roleId = $request->role_id;
        if (!$roleId) {
            $defaultRole = \App\Models\Role::where('name', 'staff')->first();
            $roleId = $defaultRole ? $defaultRole->id : null;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jenis_kelamin' => $request->jenis_kelamin,
            'phone' => $request->phone,
            'nip' => $request->nip,
            'role_id' => $roleId,
            'dinas_id' => $request->dinas_id,
            'unit_kerja_id' => $request->unit_kerja_id,
        ]);
        $user = User::with(['role', 'dinas', 'unitkerja'])->find($user->id);

        // Generate OTP for email verification
        $otpCode = rand(100000, 999999);
        Otp::create([
            'user_id' => $user->id,
            'otp_code' => (string)$otpCode,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new VerificationOtp($otpCode, $user->name));

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. Please check your email for verification code.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'role' => $user->role ? $user->role->name : null,
                    'dinas' => $user->dinas ? $user->dinas->name : null,
                    'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deleted_at' => $user->deleted_at,
                ]
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

        // Find user first
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Find OTP by user_id and otp_code
        $otp = Otp::where('user_id', $user->id)
            ->where('otp_code', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        $user = User::with(['role', 'dinas', 'unitkerja'])->find($user->id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Delete OTP after verification
        $otp->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'data' => [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'nip' => $user->nip,
                    'jenis_kelamin' => $user->jenis_kelamin,
                    'role' => $user->role ? $user->role->name : null,
                    'dinas' => $user->dinas ? $user->dinas->name : null,
                    'unit_kerja' => $user->unitkerja ? $user->unitkerja->name : null,
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'deleted_at' => $user->deleted_at,
                ]
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

        $user = User::with(['role', 'dinas', 'unitkerja', ])->where('email', $request->email)->first();

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
            'otp_code' => (string)$otpCode,
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

        // Find user first
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Find OTP by user_id and otp_code
        $otp = Otp::where('user_id', $user->id)
            ->where('otp_code', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete OTP after use
        $otp->delete();

        // Revoke all tokens
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ]);
    }
}