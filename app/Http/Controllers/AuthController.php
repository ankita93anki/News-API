<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new User
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Optionally create a token right after registration
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    /**
     * Login a User
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'token' => $token
        ], 200);
    }

    /**
     * Logout the authenticated User
     */
    public function logout(Request $request)
    {
       try {
        $user = $request->user();

        // If there's no authenticated user, return a suitable response.
        if (!$user) {
            return response()->json(['message' => 'No user is currently logged in.'], 401);
        }

        // Revoke the current access token of the authenticated user.
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);

        } catch (\Exception $e) {
            // Handle any unexpected exceptions that may occur
            return response()->json([
                'message' => 'An error occurred during logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send a password reset link to the given email.
     * This corresponds to the "forgot password" action.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)]);
        }

        return response()->json(['message' => __($status)], 400);
    }

    /**
     * Handle the actual password reset.
     * This will require a token (sent via email), the user’s email, and the new password.
     */
    public function resetPassword(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    // Attempt to reset the user's password
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            // Update the user's password
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    // Check the reset status and return the appropriate response
    if ($status == Password::PASSWORD_RESET) {
        return response()->json(['message' => 'Password reset successfully.']);
    }

    return response()->json(['message' => __($status)], 400);
}

}