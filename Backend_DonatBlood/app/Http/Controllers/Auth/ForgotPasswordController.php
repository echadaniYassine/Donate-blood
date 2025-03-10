<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Handle forgot password request (send reset link)
     */
    public function forgotPassword(Request $request)
    {
        // Validate email input
        $request->validate(['email' => 'required|email|exists:users,email']);

        $email = $request->email;
        $token = Str::random(60);

        // Store the reset token in the database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        // Send the reset email (you can implement this later with your email view)
        $resetLink = url('/reset-password?email=' . urlencode($email) . '&token=' . $token);
        Mail::send('emails.password-reset', ['link' => $resetLink], function ($message) use ($email) {
            $message->to($email)
                    ->subject('Password Reset Request');
        });

        return response()->json([
            'message' => 'Password reset link sent successfully.',
        ]);
    }

    /**
     * Handle reset password request
     */
    public function resetPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Fetch the reset token from the database
        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        // Check if the token is valid and has not expired
        if (!$reset || !Hash::check($request->token, $reset->token)) {
            return response()->json(['message' => 'Invalid or expired token'], 400);
        }

        // Check if the token has expired (valid for 60 minutes)
        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return response()->json(['message' => 'Token has expired'], 400);
        }

        // Update the user password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Delete the token after successful password reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}
