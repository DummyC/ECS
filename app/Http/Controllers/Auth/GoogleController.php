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

        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'password' => bcrypt(str()->random(16)), // Random password
            ]
        );

        // Log in the user
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
