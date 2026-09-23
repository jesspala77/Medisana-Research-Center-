<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class MicrosoftAuthController extends Controller
{
    /**
     * Redirect the user to Microsoft for authentication.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('microsoft')
            ->scopes(config('services.microsoft.scopes', []))
            ->redirect();
    }

    /**
     * Handle the callback from Microsoft.
     */
    public function callback(): RedirectResponse
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();

            $microsoftEmail = $microsoftUser->getEmail();
            $microsoftId = $microsoftUser->getId();

            /*
             * First use the currently logged-in SynNexus user.
             * This allows your Microsoft business email to be linked
             * to your existing SynNexus account with a different email.
             */
            $user = Auth::user();

            /*
             * If the user is not already logged in, attempt to find an
             * account that was previously connected to Microsoft.
             */
            if (! $user) {
                $user = User::where('microsoft_id', $microsoftId)
                    ->when(
                        $microsoftEmail,
                        fn ($query) => $query
                            ->orWhere('microsoft_email', $microsoftEmail)
                            ->orWhere('email', $microsoftEmail)
                    )
                    ->first();
            }

            /*
             * Do not automatically create a new account because the
             * Microsoft email may differ from the existing local email.
             */
            if (! $user) {
                return redirect('/')
                    ->with(
                        'error',
                        'No matching SynNexus user was found. Log into SynNexus first, then connect Microsoft again.'
                    );
            }

            /*
             * Assign fields directly so this works even before the
             * Microsoft fields are added to the model's $fillable array.
             */
            $user->microsoft_id = $microsoftId;
            $user->microsoft_email = $microsoftEmail;
            $user->microsoft_access_token = $microsoftUser->token;
            $user->microsoft_refresh_token = $microsoftUser->refreshToken ?? null;

            $expiresIn = (int) ($microsoftUser->expiresIn ?? 0);

            $user->microsoft_token_expires_at = $expiresIn > 0
                ? now()->addSeconds($expiresIn)
                : null;

            $user->save();

            Auth::login($user);

            request()->session()->regenerate();

            return redirect('/dashboard')
                ->with('success', 'Microsoft account connected successfully.');
        } catch (Throwable $exception) {
            report($exception);

            return redirect('/')
                ->with(
                    'error',
                    'Microsoft authentication failed. Check the Laravel log for details.'
                );
        }
    }
}
