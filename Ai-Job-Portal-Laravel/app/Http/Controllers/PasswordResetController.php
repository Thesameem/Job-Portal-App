<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    /**
     * Request OTP for password reset
     */
    public function requestOtp(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string',
            ]);

            // Format phone number to E.164 format if not already
            $phoneNumber = $request->phone_number;
            if (!str_starts_with($phoneNumber, '+')) {
                $phoneNumber = '+' . $phoneNumber;
            }

            // Check if user exists with this phone number
            $user = User::where('phone_number', $phoneNumber)->first();
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'reason' => 'No user found with this phone number',
                    'response' => null
                ], 404);
            }

            // Generate OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            
            // Store OTP in password_reset_tokens table
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $phoneNumber], // Using email column for phone number
                [
                    'token' => $otp,
                    'created_at' => Carbon::now()
                ]
            );

            // For development, return OTP in response
            return response()->json([
                'error' => false,
                'reason' => 'OTP generated successfully',
                'response' => [
                    'otp' => $otp, // Remove this in production
                    'expires_in' => 10 // minutes
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to generate OTP: ' . $e->getMessage(),
                'response' => null
            ], 500);
        }
    }

    /**
     * Verify OTP and generate reset token
     */
    public function verifyOtp(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|min:10',
                'otp' => 'required|string'
            ]);

            // Format phone number
            $phoneNumber = $request->phone_number;
            if (!str_starts_with($phoneNumber, '+')) {
                $phoneNumber = '+' . $phoneNumber;
            }

            // Verify OTP
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $phoneNumber) // Using email column for phone number
                ->where('token', $request->otp)
                ->where('created_at', '>', Carbon::now()->subMinutes(10))
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Invalid or expired OTP',
                    'response' => null
                ], 400);
            }

            // Generate a temporary token for password reset
            $resetToken = Str::random(60);

            // Update the token in the database
            DB::table('password_reset_tokens')
                ->where('email', $phoneNumber)
                ->update([
                    'token' => $resetToken,
                    'created_at' => Carbon::now()
                ]);

            return response()->json([
                'error' => false,
                'reason' => 'OTP verified successfully',
                'response' => [
                    'reset_token' => $resetToken
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to verify OTP: ' . $e->getMessage(),
                'response' => null
            ], 500);
        }
    }

    /**
     * Reset password using reset token
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|min:10',
                'reset_token' => 'required|string',
                'password' => 'required|string|min:8'
            ]);

            // Format phone number
            $phoneNumber = $request->phone_number;
            if (!str_starts_with($phoneNumber, '+')) {
                $phoneNumber = '+' . $phoneNumber;
            }

            // Verify reset token
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $phoneNumber)
                ->where('token', $request->reset_token)
                ->where('created_at', '>', Carbon::now()->subMinutes(10))
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'error' => true,
                    'reason' => 'Invalid or expired reset token',
                    'response' => null
                ], 400);
            }

            // Update password
            $user = User::where('phone_number', $phoneNumber)->first();
            if (!$user) {
                return response()->json([
                    'error' => true,
                    'reason' => 'User not found',
                    'response' => null
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Delete used reset token
            DB::table('password_reset_tokens')
                ->where('email', $phoneNumber)
                ->delete();

            return response()->json([
                'error' => false,
                'reason' => 'Password reset successfully',
                'response' => null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'reason' => 'Failed to reset password: ' . $e->getMessage(),
                'response' => null
            ], 500);
        }
    }
}