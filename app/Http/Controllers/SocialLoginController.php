<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and log them in or register.
     */
    public function handleGoogleCallback()
    {
        try {
            // Only this line! Do not call redirect() here.
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    $user = User::create([
                        'google_id'         => $googleUser->id,
                        'name'              => $googleUser->name ?? 'Google User',
                        'email'             => $googleUser->email,
                        'username'          => $this->generateUniqueUsername($googleUser->name ?? $googleUser->email),
                        'email_verified_at' => now(),
                        'password'          => null,
                    ]);
                }
            }

            Auth::login($user, true);

            return redirect()->intended('dashboard');
        } catch (Exception $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Login with Google failed. Please try again.');
        }
    }

    /**
     * Generates a unique, non-empty username from a given name or string.
     */
    private function generateUniqueUsername(string $name): string
    {
        // Fallback if name is empty
        $username = trim(Str::slug($name));
        if (empty($username)) {
            $username = 'user_' . Str::random(8);
        }
        $baseUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
