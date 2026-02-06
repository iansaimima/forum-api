<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
        ], [
            'name.required'     => 'Name is required.',
            'username.required' => 'Username is required.',
            'username.unique'   => 'The username has already been taken.',
            'email.required'    => 'Email is required.',
            'email.unique'      => 'The email has already been taken.',
            'password.required' => 'Password is required.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Registration validation failed',
                'errors'  => $validator->errors(),
            ], 200); // Custom status code 200
        }
        $validated = $validator->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Format timestamps
        if ($user->created_at) {
            $user->created_at_formatted = date('d M Y, H:i', strtotime($user->created_at));
            $user->created_at_ago = $user->created_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->created_at->diffForHumans();
        }

        if ($user->updated_at) {
            $user->updated_at_formatted = date('d M Y, H:i', strtotime($user->updated_at));
            $user->updated_at_ago = $user->updated_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->updated_at->diffForHumans();
        }

        $userData = $user->toArray();
        unset($userData['created_at'], $userData['updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data'    => [
                'user'         => $userData,
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(Request $request)
    {
        // Cara 2: Validation dengan custom status code menggunakan Validator facade
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email is required',
            'email.email'       => 'Email must be a valid email address',
            'password.required' => 'Password is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Login validation failed',
                'errors'  => $validator->errors(),
            ], 200); // Custom status code 200
        }

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found with this email.',
            ], 200); // Custom status code 200
        }

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password.',
            ], 200); // Custom status code 200
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        // Format timestamps
        if ($user->created_at) {
            $user->created_at_formatted = date('d M Y, H:i', strtotime($user->created_at));
            $user->created_at_ago = $user->created_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->created_at->diffForHumans();
        }

        if ($user->updated_at) {
            $user->updated_at_formatted = date('d M Y, H:i', strtotime($user->updated_at));
            $user->updated_at_ago = $user->updated_at->diffInMinutes(now()) < 5
                ? 'just now'
                : $user->updated_at->diffForHumans();
        }

        $userData = $user->toArray();
        unset($userData['created_at'], $userData['updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data'    => [
                'user'         => $userData,
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ],
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Send password reset link.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email is required',
            'email.email'    => 'Email must be a valid email address',
            'email.exists'   => 'No user found with this email address',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Forgot password validation failed',
                'errors'  => $validator->errors(),
            ], 200); // Custom status code 200
        }

        $status = Password::broker('users')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset link sent to your email',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send password reset link',
        ], 200); // Custom status code 200
    }

    /**
     * Reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
