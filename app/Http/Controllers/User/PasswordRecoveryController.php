<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class PasswordRecoveryController extends Controller
{
    public function show()
    {
        $pageTitle = 'Password Recovery';
        return view('Template::user.auth.recovery', compact('pageTitle'));
    }

    public function recover(Request $request)
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $request->validate([
            'username' => ['required','string'],
            'recovery_word' => ['required','string','min:4','max:100'],
            'password' => ['required','confirmed', $passwordValidation],
        ]);

        $ip = $request->ip();
        $cacheKey = 'recovery:' . strtolower($request->username) . ':' . $ip;
        if (RateLimiter::tooManyAttempts($cacheKey, 5)) {
            $seconds = RateLimiter::availableIn($cacheKey);
            return back()->withErrors(['username' => __('Too many attempts. Try again in :seconds seconds.', ['seconds' => $seconds])]);
        }

        $user = User::where('username', strtolower($request->username))->first();
        if (!$user || empty($user->recovery_word) || !Hash::check($request->recovery_word, $user->recovery_word)) {
            RateLimiter::hit($cacheKey, 60);
            return back()->withErrors(['username' => __('Invalid username or recovery word')]);
        }

        $user->password = Hash::make($request->password);
        $user->save();
        RateLimiter::clear($cacheKey);

        $notify[] = ['success', 'Password has been reset. You can now login.'];
        return to_route('user.login')->withNotify($notify);
    }
}
