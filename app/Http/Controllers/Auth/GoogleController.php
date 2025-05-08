<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Restrict to @bisu.edu.ph emails
        if (!str_ends_with($googleUser->email, '@bisu.edu.ph')) {
            return redirect('/login')->withErrors(['email' => 'Only @bisu.edu.ph emails are allowed.']);
        }

        // Define admin emails
        $adminEmails = [
            'admin@bisu.edu.ph',
            'dean@bisu.edu.ph',
            // Add more admin emails here
        ];

        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'password' => bcrypt(str()->random(16)), // Random password
            ]
        );

        // Update admin status if email is in admin list
        if (in_array($googleUser->email, $adminEmails) && !$user->is_admin) {
            $user->update(['is_admin' => true]);
        }


        // Log in the user
        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}
