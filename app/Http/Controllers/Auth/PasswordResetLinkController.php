<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use App\Helpers\OtpHelper;
use App\Mail\SendPasswordOtp;

class PasswordResetLinkController extends Controller
{
    /**
     * Show Forgot Password Page
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to email
     */
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }

        // Generate OTP
        $otp = OtpHelper::generate();

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Str::random(60),
                'otp' => $otp,
                'created_at' => now(),
                'otp_expires_at' => now()->addMinutes(5)
            ]
        );

        Mail::to($email)->send(new SendPasswordOtp($otp));

        return redirect()
            ->route('password.verify-otp', ['email' => $email])
            ->with('success', 'OTP sent to your email. Expires in 5 minutes.');
    }

    /**
     * Show OTP Verification Page
     */
    public function showOtpForm(Request $request)
    {
        return view('auth.verify-otp', [
            'email' => $request->email
        ]);
    }

    /**
     * Validate OTP
     */
    public function checkOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'OTP not found.']);
        }

        if (now()->greaterThan($record->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired.']);
        }

        if ($record->otp != $request->otp) {
            return back()->withErrors(['otp' => 'Incorrect OTP.']);
        }

        // OTP valid → Redirect to reset-password form
        return redirect()->route('password.reset.form', [
            'email' => $request->email,
            'token' => $record->token
        ]);
    }

    /**
     * Show Reset Password Form
     */
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->email,
            'token' => $request->token
        ]);
    }

    /**
     * Handle password update
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['password' => 'Invalid reset token.']);
        }

        // Update password
        User::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully.');
    }
}
